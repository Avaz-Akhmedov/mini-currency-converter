<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private array $rates = [
        'RUB' => 1,
        'USD' => 90,
        'EUR' => 100,
    ];

    public function __invoke(Request $request)
    {
        $currency = strtoupper($request->input('currency', 'RUB'));

        if (!array_key_exists($currency, $this->rates)) {
            return response()->json([
                'message' => 'Неверная валюта. Доступно: RUB, USD, EUR.'
            ], 400);
        }

        $products = Product::query()->latest()
            ->paginate()
            ->getCollection()
            ->transform(function (Product $product) use ($currency) {
                $convertedPrice = $product->price / $this->rates[$currency];

                $formattedPrice = match ($currency) {
                    'USD' => '$' . number_format($convertedPrice, 2),
                    'EUR' => '€' . number_format($convertedPrice, 2),
                    default => number_format($product->price, 0, '', ' ') . ' ₽',
                };

                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $formattedPrice,
                ];
            });

        return response()->json($products);
    }
}
