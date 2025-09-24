<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products and users for the orders
        $products = Product::where('is_active', true)->get();
        $users = User::all();
        
        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No products or users found. Please run other seeders first.');
            return;
        }

        // Create customers if they don't exist
        $customers = Customer::all();
        if ($customers->isEmpty()) {
            $this->createCustomers();
            $customers = Customer::all();
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['credit_card', 'debit_card', 'pix', 'boleto', 'paypal'];
        
        $cities = [
            'São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Salvador', 'Brasília',
            'Fortaleza', 'Manaus', 'Curitiba', 'Recife', 'Porto Alegre',
            'Belém', 'Goiânia', 'Guarulhos', 'Campinas', 'São Luís'
        ];
        
        $states = [
            'SP', 'RJ', 'MG', 'BA', 'DF', 'CE', 'AM', 'PR', 'PE', 'RS',
            'PA', 'GO', 'MA', 'SC', 'ES'
        ];

        for ($i = 1; $i <= 30; $i++) {
            // Create order with random data
            $user = $users->random();
            $customer = $customers->random();
            $status = $statuses[array_rand($statuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            $city = $cities[array_rand($cities)];
            $state = $states[array_rand($states)];
            
            // Random date between 30 days ago and now
            $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $order = Order::create([
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'status' => $status,
                'total_amount' => 0, // Will be calculated after adding items
                'shipping_address' => json_encode([
                    'name' => $customer->name,
                    'street' => 'Rua ' . $this->generateRandomStreet(),
                    'number' => rand(1, 9999),
                    'complement' => rand(1, 10) > 7 ? 'Apto ' . rand(1, 200) : null,
                    'neighborhood' => $this->generateRandomNeighborhood(),
                    'city' => $city,
                    'state' => $state,
                    'zipcode' => $this->generateZipCode(),
                    'country' => 'Brasil'
                ]),
                'billing_address' => json_encode([
                    'name' => $customer->name,
                    'street' => 'Rua ' . $this->generateRandomStreet(),
                    'number' => rand(1, 9999),
                    'complement' => rand(1, 10) > 7 ? 'Apto ' . rand(1, 200) : null,
                    'neighborhood' => $this->generateRandomNeighborhood(),
                    'city' => $city,
                    'state' => $state,
                    'zipcode' => $this->generateZipCode(),
                    'country' => 'Brasil'
                ]),
                'payment_method' => $paymentMethod,
                'notes' => rand(1, 10) > 7 ? $this->generateRandomNote() : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ]);

            // Add 1 to 5 random items to the order
            $itemCount = rand(1, 5);
            $selectedProducts = $products->random($itemCount);
            $totalAmount = 0;

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $itemTotal = $price * $quantity;
                $totalAmount += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt
                ]);
            }

            // Update order total
            $order->update(['total_amount' => $totalAmount]);
        }

        $this->command->info('Created 30 sample orders with items.');
    }

    private function createCustomers(): void
    {
        $customerNames = [
            'João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa', 'Carlos Ferreira',
            'Lucia Rodrigues', 'Roberto Alves', 'Fernanda Lima', 'Marcos Pereira', 'Juliana Souza',
            'Rafael Martins', 'Camila Barbosa', 'Diego Rocha', 'Patricia Nunes', 'André Gomes',
            'Beatriz Dias', 'Thiago Moreira', 'Larissa Cardoso', 'Felipe Ramos', 'Gabriela Lopes'
        ];

        $domains = ['gmail.com', 'hotmail.com', 'yahoo.com.br', 'outlook.com', 'uol.com.br'];

        foreach ($customerNames as $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@' . $domains[array_rand($domains)];
            $phone = sprintf('(%02d) %04d-%04d', rand(11, 99), rand(1000, 9999), rand(1000, 9999));

            Customer::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'is_active' => true,
            ]);
        }

        $this->command->info('Created ' . count($customerNames) . ' sample customers.');
    }

    private function generateRandomStreet(): string
    {
        $streets = [
            'das Flores', 'dos Santos', 'da Paz', 'do Sol', 'das Palmeiras',
            'da Liberdade', 'do Comércio', 'das Rosas', 'da Esperança', 'do Progresso',
            'das Acácias', 'da Vitória', 'do Amor', 'das Margaridas', 'da Fé'
        ];
        return $streets[array_rand($streets)];
    }

    private function generateRandomNeighborhood(): string
    {
        $neighborhoods = [
            'Centro', 'Vila Nova', 'Jardim das Flores', 'Bela Vista', 'Copacabana',
            'Ipanema', 'Leblon', 'Tijuca', 'Botafogo', 'Flamengo',
            'Barra da Tijuca', 'Recreio', 'Jacarepaguá', 'Campo Grande', 'Santa Teresa'
        ];
        return $neighborhoods[array_rand($neighborhoods)];
    }

    private function generateZipCode(): string
    {
        return sprintf('%05d-%03d', rand(10000, 99999), rand(100, 999));
    }

    private function generateRandomNote(): string
    {
        $notes = [
            'Entregar apenas após as 18h',
            'Deixar com o porteiro',
            'Produto frágil, manuseio com cuidado',
            'Entregar na portaria',
            'Cliente solicita entrega rápida',
            'Verificar produto antes da entrega',
            'Entregar apenas para o titular',
            'Produto para presente'
        ];
        return $notes[array_rand($notes)];
    }
}
