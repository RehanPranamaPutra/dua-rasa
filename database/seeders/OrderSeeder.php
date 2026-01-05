<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\UserCustomer;
use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = UserCustomer::all();
        $addresses = Address::all();
        $products  = Product::all();

        if ($customers->count() == 0 || $addresses->count() == 0 || $products->count() == 0) {
            $this->command->error("Seeder gagal: butuh data customer, address, dan product!");
            return;
        }

        for ($i = 1; $i <= 40; $i++) {

            // RANDOM CUSTOMER & ADDRESS
            $customer = $customers->random();
            $address = $addresses->random();

            // RANDOM ORDER STATUS
            $status = fake()->randomElement(['new', 'processing', 'shipped', 'delivered', 'cancelled']);

            // RANDOM SHIPPING SERVICE & COST
            $shippingService = fake()->randomElement(['JNE', 'TIKI', 'POS', 'SiCepat']);
            $shippingCost = fake()->numberBetween(10000, 30000);

            // CREATE ORDER
            $order = Order::create([
                'customer_id'     => $customer->id,
                'address_id'      => $address->id,
                'invoice_number'  => 'INV-' . date('Y') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'total_price'     => 0, // akan update setelah detail
                'shipping_service'=> $shippingService,
                'shipping_cost'   => $shippingCost,
                'order_status'    => $status,
                'payment_status'  => 'Pending', // default sementara
            ]);

            // RANDOM ORDER ITEMS
            $detailCount = fake()->numberBetween(2, 8);
            $total = 0;

            for ($j = 1; $j <= $detailCount; $j++) {
                $product = $products->random();
                $amount = fake()->numberBetween(1, 5);

                $subTotal = $product->price * $amount;
                $total += $subTotal;

                OrderDetail::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'address_id'   => $address->id,
                    'product_name' => $product->name,
                    'price'        => $product->price,
                    'amount'       => $amount,
                    'total'        => $subTotal,
                ]);
            }

            // UPDATE TOTAL PRICE (produk + ongkir)
            $order->update([
                'total_price'    => $total + $shippingCost,
            ]);

            // PAYMENT MOCK: update payment_status langsung di order
            $isPaid = fake()->boolean(70); // 70% sukses
            $order->update([
                'payment_status' => $isPaid ? 'Berhasil' : 'Pending',
            ]);
        }
    }
}
