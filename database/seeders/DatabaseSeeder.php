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
        // 1. Cipta Akaun Pengguna (Termasuk phone_number yang baru ditambah)
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@visionconcept.com',
            'phoneNO' => '01112345678', // Lajur baru
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $salesperson = User::create([
            'name' => 'Salesperson 1',
            'email' => 'salesperson@visionconcept.com',
            'phoneNO' => '0123456789', // Lajur baru
            'password' => Hash::make('password123'),
            'role' => 'salesperson',
        ]);

        User::create([
            'name' => 'Store Manager',
            'email' => 'manager@visionconcept.com',
            'phoneNO' => '0131234567', // Lajur baru
            'password' => Hash::make('password123'),
            'role' => 'manager',
        ]);

        // 2. Cipta Data Pelanggan Dummy
        $customer = Customer::create([
            'customerName' => 'Ali bin Abu',
            'customerEmail' => 'ali@example.com',
            'customerPhoneNumber' => '0198765432',
        ]);

        // 3. Cipta Data Temujanji Dummy (Termasuk created_by yang baru ditambah)
        $appointment = Appointment::create([
            'customerID' => $customer->id,
            'status' => 'Pending',
            'appointmentDate' => now()->toDateString(),
            'appointmentTime' => now()->toTimeString(),
            'created_by' => $salesperson->id, // Lajur baru: merujuk kepada ID staff yang merekodkan
        ]);

        // 4. Cipta Data Keadaan Mata (Eye Condition) berkaitan Appointment
        EyeCondition::create([
            'appointmentID' => $appointment->appointmentID,
            'rightPower' => -1.50,
            'leftPower' => -1.75,
            'rightCylinder' => -0.50,
            'leftCylinder' => -0.75,
            'rightAxis' => 180,
            'leftAxis' => 90,
            'pupilDistance' => 63.5,
            'salespersonID' => $salesperson->id,
        ]);

        // 5. Cipta Senarai Produk / Item Kedai Cermin Mata (Lengkap dengan jenis type, brand, price)
        $frame = Item::create([
            'name' => 'Classic Titanium Frame (Black)',
            'type' => 'Frame',
            'brand' => 'Titanium',
            'price' => 150.00,
            'stock' => 15
        ]);

        $lens = Item::create([
            'name' => 'Anti-Blue Light Lenses (Standard)',
            'type' => 'Lens',
            'brand' => 'VisionCare',
            'price' => 100.00,
            'stock' => 50
        ]);

        // 6. Cipta Data Pesanan Dummy (Orders) (Termasuk salesperson_id)
        $order = Order::create([
            'customer_id'       => $customer->id,
            'salesperson_id'    => $salesperson->id, // Lajur baru: merujuk jurujual yang handle order
            'total_price'       => 250,
            'amount_paid'       => 100,
            'remaining_balance' => 150,
            'payment_status'    => 'Partial',
            'order_status'      => 'Processing',
            'order_date'        => now()->toDateString(),
        ]);

        // 7. Cipta Hubungan Item di dalam Order Items (Termasuk kuantiti/quantity yang baru ditambah)
        DB::table('order_items')->insert([
            [
                'order_id'   => $order->id,
                'item_id'    => $frame->id,
                'price'      => 150.00,
                'quantity'   => 1, // Lajur baru: kuantiti bingkai yang dibeli
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id'   => $order->id,
                'item_id'    => $lens->id,
                'price'      => 100.00,
                'quantity'   => 1, // Lajur baru: kuantiti kanta yang dibeli
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
