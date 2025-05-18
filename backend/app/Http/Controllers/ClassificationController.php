<?php

namespace App\Http\Controllers;

use App\Models\ClassificationData;
use App\Models\Classifications;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ClassificationController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $classifications = Classifications::all();
            return response()->json($classifications);
        } catch (\Exception $e) {
            Log::error('获取主分类失败: ' . $e->getMessage());
            return response()->json(['error' => '获取主分类失败'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $this->validateClassificationStore($request);
            $classification = Classifications::create([
                'name' => $validated['name'],
                'openLevel' => 1,
                'isDisabled' => 0,
            ]);
            return response()->json($classification, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            Log::error('添加主分类失败: ' . $e->getMessage());
            return response()->json(['error' => '添加主分类失败'], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $class = Classifications::findOrFail($id);
            $class->update($request->only(['name', 'openLevel', 'isDisabled']));
            return response()->json($class);
        } catch (\Exception $e) {
            Log::error("修改主分类失败: " . $e->getMessage());
            return response()->json(['error' => '修改主分类失败'], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $class = Classifications::findOrFail($id);
            $class->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error("删除主分类失败: " . $e->getMessage());
            return response()->json(['error' => '删除主分类失败'], 500);
        }
    }

    public function getClassificationData(int $dataId, ?string $parentId): JsonResponse
    {
        try {
            $query = ClassificationData::where('dataId', $dataId);

            if ($parentId && $parentId !== 'null') {
                $query->where('parent_id', $parentId);
            } else {
                $query->whereNull('parent_id');
            }

            $results = $query->orderBy('code')->get();

            return response()->json($results);
        } catch (\Exception $e) {
            Log::error("获取子分类失败: " . $e->getMessage());
            return response()->json(['error' => '获取子分类失败'], 500);
        }
    }

    public function addClassificationData(Request $request): JsonResponse
    {
        try {
            $validated = $this->validateClassificationData($request);

            // 自动推断 level
            $level = 1;
            if ($validated['parent_id']) {
                $parent = ClassificationData::find($validated['parent_id']);
                if ($parent) {
                    $level = $parent->level + 1;
                }
            }

            $classificationData = ClassificationData::create([
                'dataId' => $validated['dataId'],
                'name' => $validated['name'],
                'fullName' => $validated['fullName'],
                'code' => $validated['code'],
                'parent_id' => $validated['parent_id'] ?? null,
                'level' => $level,
            ]);

            return response()->json($classificationData, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            Log::error("添加子分类失败: " . $e->getMessage());
            return response()->json(['error' => '添加子分类失败'], 500);
        }
    }

    public function updateClassificationData(Request $request, int $id): JsonResponse
    {
        try {
            $classificationData = ClassificationData::findOrFail($id);
            $validated = $this->validateClassificationData($request, false);

            $classificationData->update([
                'name' => $validated['name'] ?? $classificationData->name,
                'fullName' => $validated['fullName'] ?? $classificationData->fullName,
                'code' => $validated['code'] ?? $classificationData->code,
                'parent_id' => $validated['parent_id'] ?? $classificationData->parent_id,
            ]);

            return response()->json($classificationData);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            Log::error("修改子分类失败 ID:$id - " . $e->getMessage());
            return response()->json(['error' => '修改子分类失败'], 500);
        }
    }

    public function deleteClassificationData(int $id): JsonResponse
    {
        try {
            $classificationData = ClassificationData::findOrFail($id);
            $classificationData->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error("删除子分类失败 ID:$id - " . $e->getMessage());
            return response()->json(['error' => '删除子分类失败'], 500);
        }
    }

    private function validateClassificationStore(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
        ]);
    }

    private function validateClassificationData(Request $request, bool $isRequired = true): array
    {
        return $request->validate([
            'dataId' => $isRequired ? 'required|integer' : 'nullable|integer',
            'name' => $isRequired ? 'required|string|max:255' : 'nullable|string|max:255',
            'fullName' => $isRequired ? 'required|string|max:255' : 'nullable|string|max:255',
            'code' => $isRequired ? 'required|string|max:255' : 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
        ]);
    }
}
