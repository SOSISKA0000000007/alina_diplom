<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RentalProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = RentalProduct::all();
        return view('admin.rental-products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rental-products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ограничение на тип и размер файла
            'sizes' => 'required|array',
            'sizes.*' => 'required|string|in:xs,s,m,l,xl,xxl',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);

        $sizesQuantity = [];
        foreach ($validated['sizes'] as $index => $size) {
            $sizesQuantity[$size] = $validated['quantities'][$index];
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $product = RentalProduct::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'price' => $validated['price'],
            'sizes_quantity' => $sizesQuantity,
        ]);

        return redirect()->route('admin.rental-products.index')
            ->with('success', 'Товар успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentalProduct $rentalProduct)
    {
        return view('admin.rental-products.edit', compact('rentalProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentalProduct $rentalProduct)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array',
            'sizes.*' => 'required|string|in:xs,s,m,l,xl,xxl',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);

        $sizesQuantity = [];
        foreach ($validated['sizes'] as $index => $size) {
            $sizesQuantity[$size] = $validated['quantities'][$index];
        }

        $imagePath = $rentalProduct->image;
        if ($request->hasFile('image')) {
            // Удаляем старую фотографию, если она существует
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $rentalProduct->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'price' => $validated['price'],
            'sizes_quantity' => $sizesQuantity,
        ]);

        return redirect()->route('admin.rental-products.index')
            ->with('success', 'Товар успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentalProduct $rentalProduct)
    {
        // Удаляем фотографию при удалении товара
        if ($rentalProduct->image) {
            Storage::disk('public')->delete($rentalProduct->image);
        }

        $rentalProduct->delete();
        return redirect()->route('admin.rental-products.index')
            ->with('success', 'Товар успешно удален');
    }
}
