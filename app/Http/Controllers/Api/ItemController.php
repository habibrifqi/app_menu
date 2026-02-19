<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'price' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'name'  => $validated['name'],
            'price' => $validated['price'],
            'image' => $imagePath,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Item created successfully',
            'data'    => [
                'id'    => $item->id,
                'name'  => $item->name,
                'price' => $item->price,
                'image' => $item->image,
                'image_url' => Storage::disk('public')->url($item->image),
            ]
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:100',
            'price' => 'sometimes|required|integer|min:0',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }

            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        // dd($request->all());

        $item->update($validated);

        // dd($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Item updated successfully',
            'data'    => [
                'id'    => $item->id,
                'name'  => $item->name,
                'price' => $item->price,
                'image' => $item->image,
                'image_url' => Storage::disk('public')->url($item->image),
            ]
        ], Response::HTTP_OK);
    }
}
