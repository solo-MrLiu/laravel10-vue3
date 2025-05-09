<?php

namespace App\Http\Controllers;

use App\Models\Process;
use App\Models\ProcessInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    // 创建流程定义
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'form_id' => 'required|exists:forms,id',
            'nodes' => 'required|array',
            'edges' => 'required|array'
        ]);

        $process = Process::create($validated);
        return response()->json($process, 201);
    }

    // 启动流程实例
    public function startInstance(Request $request, Process $process): JsonResponse
    {
        $instance = ProcessInstance::create([
            'process_id' => $process->id,
            'record_id' => $request->record_id,
            'current_node_id' => $process->nodes->firstWhere('type', 'start')->id,
            'status' => 'running'
        ]);

        return response()->json($instance);
    }

    // 获取流程实例详情（含审批历史）
    public function getInstance(ProcessInstance $instance): JsonResponse
    {
        $instance->load(['process.nodes', 'history']);
        return response()->json($instance);
    }
}
    