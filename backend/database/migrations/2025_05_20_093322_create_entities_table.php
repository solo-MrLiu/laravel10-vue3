<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 创建实体表
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->unique()->comment('实体名称（英文）');
            $table->string('label', 64)->comment('实体标签（中文）');
            $table->text('comments')->nullable()->comment('实体描述');
            $table->string('main_entity', 64)->nullable()->comment('主实体名称');
            $table->boolean('had_approval')->default(false)->comment('是否需要审批');
            $table->json('settings')->nullable()->comment('实体配置信息');
            $table->unsignedInteger('sort_order')->default(0)->comment('排序号');
            $table->boolean('is_system')->default(false)->comment('是否为系统内置实体');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->timestamps();
        });

        // 创建实体字段表
        Schema::create('entity_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id')->comment('所属实体ID');
            $table->string('name', 64)->comment('字段名称（英文）');
            $table->string('label', 64)->comment('字段标签（中文）');
            $table->string('type', 32)->comment('字段类型');
            $table->boolean('is_required')->default(false)->comment('是否必填');
            $table->boolean('is_unique')->default(false)->comment('是否唯一');
            $table->boolean('is_primary_key')->default(false)->comment('是否为主键');
            $table->boolean('is_nullable')->default(true)->comment('是否可为空');
            $table->text('default_value')->nullable()->comment('默认值');
            $table->text('validation_rules')->nullable()->comment('验证规则');
            $table->json('options')->nullable()->comment('字段选项（如下拉框选项）');
            $table->unsignedInteger('sort_order')->default(0)->comment('排序号');
            $table->boolean('is_system')->default(false)->comment('是否为系统字段');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->timestamps();

            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
            $table->unique(['entity_id', 'name']);
        });
    }

    public function down()
    {
        // 先删除有外键依赖的表
        Schema::dropIfExists('entity_fields');
        Schema::dropIfExists('entities');
    }
};
