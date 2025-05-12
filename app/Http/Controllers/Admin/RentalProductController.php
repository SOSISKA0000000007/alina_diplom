<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'sizes' => 'required|array',
                'sizes.*' => 'required|string|in:xs,s,m,l,xl,xxl',
                'quantities' => 'required|array',
                'quantities.*' => 'required|integer|min:1',
            ]);

            Log::info('Validated data for store', $validated);

            $sizesQuantity = [];
            foreach ($validated['sizes'] as $index => $size) {
                $sizesQuantity[$size] = $validated['quantities'][$index];
            }

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $path = $image->store('rental-products', 'public');
                        $imagePaths[] = $path;
                        Log::info('Image uploaded', ['path' => $path]);
                    } else {
                        Log::error('Invalid image file uploaded', ['file' => $image->getClientOriginalName()]);
                    }
                }
            }

            $product = RentalProduct::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'images' => $imagePaths,
                'sizes_quantity' => $sizesQuantity,
            ]);

            Log::info('Rental product created', ['product_id' => $product->id]);

            return redirect()->route('admin.rental-products.index')
                ->with('success', 'Товар успешно создан');
        } catch (\Exception $e) {
            Log::error('Error creating rental product: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['images' => 'Ошибка при создании товара: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Реализуйте, если нужно
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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'sizes' => 'required|array',
                'sizes.*' => 'required|string|in:xs,s,m,l,xl,xxl',
                'quantities' => 'required|array',
                'quantities.*' => 'required|integer|min:1',
            ]);

            Log::info('Validated data for update', $validated);

            $sizesQuantity = [];
            foreach ($validated['sizes'] as $index => $size) {
                $sizesQuantity[$size] = $validated['quantities'][$index];
            }

            $imagePaths = $rentalProduct->images ?? [];
            if ($request->hasFile('images')) {
                // Удаляем старые изображения, если загружаются новые
                foreach ($imagePaths as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $path = $image->store('rental-products', 'public');
                        $imagePaths[] = $path;
                        Log::info('Image uploaded during update', ['path' => $path]);
                    } else {
                        Log::error('Invalid image file uploaded during update', ['file' => $image->getClientOriginalName()]);
                    }
                }
            }

            $rentalProduct->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'images' => $imagePaths,
                'sizes_quantity' => $sizesQuantity,
            ]);

            Log::info('Rental product updated', ['product_id' => $rentalProduct->id]);

            return redirect()->route('admin.rental-products.index')
                ->with('success', 'Товар успешно обновлен');
        } catch (\Exception $e) {
            Log::error('Error updating rental product: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['images' => 'Ошибка при обновлении товара: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentalProduct $rentalProduct)
    {
        try {
            // Удаляем все изображения, связанные с товаром
            if ($rentalProduct->images) {
                foreach ($rentalProduct->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $rentalProduct->delete();
            Log::info('Rental product deleted', ['product_id' => $rentalProduct->id]);

            return redirect()->route('admin.rental-products.index')
                ->with('success', 'Товар успешно удален');
        } catch (\Exception $e) {
            Log::error('Error deleting rental product: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['images' => 'Ошибка при удалении товара: ' . $e->getMessage()]);
        }
    }
}
