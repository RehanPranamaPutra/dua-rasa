<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
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
            // RANDOM ORDER STATUS
            $status = fake()->randomElement(['new', 'processing', 'shipped', 'delivered', 'cancelled']);


            // CREATE ORDER
            $order = Order::create([
                'customer_id'   => $customer->id,
                'address_id'    => $address->id,
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'total_price'   => 0, // akan update setelah detail
                'shipping_cost' => fake()->numberBetween(10000, 30000),
                'order_status'  => $status,
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
                    'order_id'    => $order->id,
                    'product_id'  => $product->id,
                    'address_id'  => $address->id,
                    'product_name' => $product->name,
                    'price'       => $product->price,
                    'amount'      => $amount,
                    'total'       => $subTotal,
                ]);
            }

            // UPDATE TOTAL PRICE
            $order->update([
                'total_price' => $total,
            ]);

            // PAYMENT MOCK
            $isPaid = fake()->boolean(70); // 70% sukses

            Payment::create([
                'order_id'       => $order->id,
                'method'         => fake()->randomElement(['Transfer Bank', 'E-Wallet', 'Dana', 'Ovo']),
                'transaction_code' => Str::upper(Str::random(10)),
                'amount'         => $total + $order->shipping_cost,
                'payment_status' => $isPaid ? 'Berhasil' : 'Pending',
                'payment_time'   => $isPaid ? now() : null,
            ]);
        }
    }
}
