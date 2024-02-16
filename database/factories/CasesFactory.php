<?php

namespace Database\Factories;

use App\Models\Cases;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CasesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $caseClients = [
            'Robert Kanyoro',
            'Bidco Africa',
            'Safaricom Kenya', 
            'KCB Bank',
            'Equity Bank',
            'Kenya Power',
            'Kenya Pipeline',
            'Kenya Airways',
            'Kenya Ports Authority',
        ];

        $stakeholders = Arr::random($caseClients, rand(1, 3));

        return [
            'user_id' => rand(1, 10),
            'title' => Arr::random(['Land Dispute', 'Contract Dispute', 'Employment Dispute', 'Insurance Claim', 'Intellectual Property Dispute']),
            'description' => Arr::random(['This is a land dispute case', 'This is a contract dispute case', 'This is an employment dispute case', 'This is an insurance claim case', 'This is an intellectual property dispute case']),
            'stakeholders' => $stakeholders,
            'status' => Arr::random(['open', 'closed']),
        ];
    }
}
