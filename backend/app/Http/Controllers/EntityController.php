<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\EntityField;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Overtrue\Pinyin\Pinyin;

class EntityController extends Controller
{
    /**
     * 实体列表
     */
    protected function index()
    {
        $entities = Entity::query()->whereNull('main_entity')->get();
        return response()->json($entities);
    }


    /**
     * 验证实体数据
     */
    protected function validateEntityData(Request $request, bool $isCreate = true): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
            ],
            'label' => 'required|string|min:2|max:50',
            'comments' => 'min:0|max:50',
        ], [
            'name.regex' => '名称必须是有效的数据库标识符（以字母开头，仅包含字母、数字和下划线）',
        ]);
    }

    /**
     * 创建新实体并同步创建数据表
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            //label转化name
            $pinyin =  new  \Overtrue\Pinyin\Pinyin();
            $request['name'] = str_replace(' ','',$pinyin->sentence($request->input('label'),''));
            // 验证并创建实体
//            $validatedData = $this->validateEntityData($request);
            Log::info($request->toArray());
            $entity = Entity::create($request->toArray());

            // 创建实体字段
            if ($request->has('fields')) {
                $this->createEntityFields($entity, $request->input('fields'));
            }

            // 同步创建数据库表
            $this->createEntityDatabaseTable($entity);

            DB::commit();
            return response()->json($entity, 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => '创建实体失败: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 创建实体字段
     */
    private function createEntityFields(Entity $entity, array $fields)
    {
        foreach ($fields as $field) {
            $entity->fields()->create($field);
        }
    }

    /**
     * 向表中添加字段
     */
    private function addFieldToTable($table, $field)
    {
        $type = match ($field['type']) {
            'string' => $table->string($field['name']),
            'text' => $table->text($field['name']),
            'integer' => $table->integer($field['name']),
            'boolean' => $table->boolean($field['name']),
            default => throw new \Exception("Unsupported field type: {$field['type']}"),
        };

        if (!empty($field['default'])) {
            $type->default($field['default']);
        }

        if (isset($field['nullable']) && $field['nullable']) {
            $type->nullable();
        }
    }


    /**
     * 更新实体并同步更新数据表结构
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            // 获取并更新实体
            $entity = Entity::findOrFail($id);
            $validatedData = $this->validateEntityData($request, false);
            $entity->update($validatedData);

            // 同步实体字段
            if ($request->has('fields')) {
                $this->syncEntityFields($entity, $request->input('fields'));
            }

            // 同步更新数据库表结构
            $this->updateEntityDatabaseTable($entity);

            DB::commit();
            return response()->json($entity);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update entity: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 删除实体并同步删除数据表
     */
    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $entity = Entity::findOrFail($id);

            // 同步删除数据库表
            $this->deleteEntityDatabaseTable($entity);

            // 删除实体
            $entity->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete entity: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 创建实体对应的数据库表
     */
    private function createEntityDatabaseTable(Entity $entity)
    {
        //添加统一前缀
        $name = 't_'.$entity->name;
        // 检查表是否已存在
        if (DB::connection()->getSchemaBuilder()->hasTable($name)) {
            throw new \Exception('Table already exists');
        }

        // 创建表
        DB::connection()->getSchemaBuilder()->create($name, function ($table) use ($entity) {
            $table->id();

            // 添加实体字段
            foreach ($entity->fields as $field) {
                $this->addFieldToTable($table, $field);
            }

            $table->timestamps();
        });
    }

    /**
     * 更新实体对应的数据库表结构
     */
    private function updateEntityDatabaseTable(Entity $entity)
    {
        // 检查表是否存在
        if (!DB::connection()->getSchemaBuilder()->hasTable($entity->name)) {
            throw new \Exception('Table does not exist');
        }

        // 获取当前表的列
        $currentColumns = DB::getSchemaBuilder()->getColumnListing($entity->name);
        $currentColumns = array_diff($currentColumns, ['id', 'created_at', 'updated_at']);

        // 获取实体字段
        $entityFields = $entity->fields->keyBy('name')->toArray();

        // 添加或修改字段
        foreach ($entityFields as $field) {
            if (in_array($field['name'], $currentColumns)) {
                // 修改现有字段
                $this->modifyFieldInTable($entity->name, $field);
                $currentColumns = array_diff($currentColumns, [$field['name']]);
            } else {
                // 添加新字段
                DB::connection()->getSchemaBuilder()->table($entity->name, function ($table) use ($field) {
                    $this->addFieldToTable($table, $field);
                });
            }
        }

        // 删除不存在的字段
        foreach ($currentColumns as $column) {
            DB::connection()->getSchemaBuilder()->table($entity->name, function ($table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }

    /**
     * 删除实体对应的数据库表
     */
    private function deleteEntityDatabaseTable(Entity $entity)
    {
        // 检查表是否存在
        if (!DB::connection()->getSchemaBuilder()->hasTable($entity->name)) {
            return;
        }

        // 删除表
        DB::connection()->getSchemaBuilder()->dropIfExists($entity->name);
    }



    // 获取实体详情（包含字段和关联实体）
    public function show($id)
    {
        $entity = Entity::with('fields', 'mainEntity')->find($id);

        if (!$entity) {
            return response()->json(['message' => '实体不存在'], 404);
        }

        return response()->json($entity);
    }

    // 获取所有主实体（非明细）
    public function getMainEntities()
    {
        $entities = Entity::where('is_detail', false)->get();
        return response()->json($entities);
    }
    // 获取实体字段
    public function fieldShow($entityId)
    {
        $fields = EntityField::where('entity_id', $entityId)->get();
        return response()->json($fields);
    }

    // 创建字段
    public function fieldStore(Request $request, $entityId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'type' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $field = EntityField::create([
            'entity_id' => $entityId,
            'name' => $request->input('name'),
            'label' => $request->input('label'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'is_required' => $request->input('is_required', false),
        ]);

        return response()->json($field, 201);
    }

    // 更新字段
    public function fieldUpdate(Request $request, $entityId, $fieldId)
    {
        $field = EntityField::where('entity_id', $entityId)->find($fieldId);

        if (!$field) {
            return response()->json(['message' => '字段不存在'], 404);
        }

        $field->update($request->only(['name', 'label', 'type', 'description', 'is_required']));

        return response()->json($field);
    }

    // 删除字段
    public function fieldDestroy($entityId, $fieldId)
    {
        $field = EntityField::where('entity_id', $entityId)->find($fieldId);

        if (!$field) {
            return response()->json(['message' => '字段不存在'], 404);
        }

        $field->delete();

        return response()->json(['message' => '字段删除成功']);
    }
}
