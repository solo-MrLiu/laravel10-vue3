<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Form::with('fields')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'entity' => 'required|string',
            'layout' => 'required|array',
            'is_default' => 'boolean'
        ]);

        $form = Form::create($validated);
        return response()->json($form, 201);
    }

    public function show(Form $form): JsonResponse
    {
        return response()->json($form->load('fields'));
    }

    public function update(Request $request, Form $form): JsonResponse
    {
        $form->update($request->validate([
            'name' => 'string|max:255',
            'layout' => 'array',
            'is_default' => 'boolean'
        ]));
        return response()->json($form);
    }

    public function destroy(Form $form): JsonResponse
    {
        $form->delete();
        return response()->json(['message' => '表单已删除']);
    }
}
    