<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\EyeCondition;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cipta Akaun Pengguna
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@visionconcept.com',
            'phoneNO' => '01112345678',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $salesperson = User::create([
            'name' => 'Salesperson 1',
            'email' => 'salesperson@visionconcept.com',
            'phoneNO' => '0123456789',
            'password' => Hash::make('password123'),
            'role' => 'salesperson',
        ]);

        User::create([
            'name' => 'Store Manager',
            'email' => 'manager@visionconcept.com',
            'phoneNO' => '0131234567',
            'password' => Hash::make('password123'),
            'role' => 'manager',
        ]);

        // 2. Cipta Data Pelanggan Dummy
        $customer = Customer::create([
            'customerName' => 'Ali bin Abu',
            'customerEmail' => 'ali@example.com',
            'customerPhoneNumber' => '0198765432',
        ]);

        // 3. Cipta Data Temujanji Dummy
        $appointment = Appointment::create([
            'customerID' => $customer->id,
            'status' => 'Pending',
            'appointmentDate' => now()->toDateString(),
            'appointmentTime' => now()->toTimeString(),
            'created_by' => $salesperson->id,
        ]);

        // 4. Cipta Data Keadaan Mata (Eye Condition) berkaitan Customer & Appointment
        EyeCondition::create([
            'customer_id'   => $customer->id, // Diselaraskan dengan struktur jadual baharu
            'appointmentID' => $appointment->appointmentID,
            'rightPower'    => -1.50,
            'leftPower'     => -1.75,
            'rightCylinder' => -0.50,
            'leftCylinder'  => -0.75,
            'rightAxis'     => 180,
            'leftAxis'      => 90,
            'pupilDistance' => 63.5,
            'salespersonID' => $salesperson->id,
        ]);

        // 5. Cipta Senarai Penuh Produk / Item Kedai Cermin Mata (Bingkai & Kanta)
        $items = [
            // Kategori Bingkai (Frames)
            [
                'name'  => 'Classic Titanium Frame (Black)',
                'type'  => 'Frame',
                'brand' => 'Titanium',
                'price' => 150.00,
                'stock' => 15
            ],
            [
                'name'  => 'Modern Round Gold Frame',
                'type'  => 'Frame',
                'brand' => 'Ray-Ban',
                'price' => 280.00,
                'stock' => 10
            ],
            [
                'name'  => 'Sporty Flexible Acetate Frame',
                'type'  => 'Frame',
                'brand' => 'Oakley',
                'price' => 320.00,
                'stock' => 8
            ],
            [
                'name'  => 'Minimalist Wire-Rim Frame',
                'type'  => 'Frame',
                'brand' => 'Vogue',
                'price' => 190.00,
                'stock' => 12
            ],

            // Kategori Kanta (Lenses)
            [
                'name'  => 'Anti-Blue Light Lenses (Standard)',
                'type'  => 'Lens',
                'brand' => 'VisionCare',
                'price' => 100.00,
                'stock' => 50
            ],
            [
                'name'  => 'Anti-Reflective Multi-Coated Lens',
                'type'  => 'Lens',
                'brand' => 'Essilor',
                'price' => 160.00,
                'stock' => 40
            ],
            [
                'name'  => 'Transition / Photochromic Lens',
                'type'  => 'Lens',
                'brand' => 'Transitions',
                'price' => 250.00,
                'stock' => 25
            ],
            [
                'name'  => 'Progressive Multi-Focal Lens',
                'type'  => 'Lens',
                'brand' => 'Hoya',
                'price' => 350.00,
                'stock' => 20
            ],
        ];

        // Simpan setiap item ke dalam pangkalan data dan simpan rujukan untuk pesanan dummy
        $createdItems = [];
        foreach ($items as $itemData) {
            $createdItems[] = Item::create($itemData);
        }

        // 6. Cipta Data Pesanan Dummy (Orders)
        $order = Order::create([
            'customer_id'       => $customer->id,
            'salesperson_id'    => $salesperson->id,
            'total_price'       => 250,
            'amount_paid'       => 100,
            'remaining_balance' => 150,
            'payment_status'    => 'Partial',
            'order_status'      => 'Processing',
            'order_date'        => now()->toDateString(),
        ]);

        // 7. Cipta Hubungan Item di dalam Order Items menggunakan item pertama dan kelima (bingkai & kanta)
        DB::table('order_items')->insert([
            [
                'order_id'   => $order->id,
                'item_id'    => $createdItems[0]->id, // Classic Titanium Frame
                'price'      => 150.00,
                'quantity'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id'   => $order->id,
                'item_id'    => $createdItems[4]->id, // Anti-Blue Light Lenses
                'price'      => 100.00,
                'quantity'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
