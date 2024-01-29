<?php

namespace Tests\Unit;

use App\Services\FundService;
use App\Models\Fund;
use App\Models\Manager;
use Database\Factories\ManagerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FundServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $fundService;

    public function setUp(): void
    {
        parent::setUp();
        $this->fundService = app(FundService::class);
    }

    public function testGetAll_ReturnsMatchingFunds()
    {
        $manager = ManagerFactory::new()->create();

        $fund1 = Fund::factory()->create([
            'name' => 'Test Fund 1',
            'start_year' => 2000,
            'manager_id' => $manager->id,
        ]);

        $fund2 = Fund::factory()->create([
            'name' => 'Test Fund 2',
            'start_year' => 2001,
            'manager_id' => $manager->id,
        ]);

        $funds = $this->fundService->getAll('Test Fund', $manager->id, 2000);

        $this->assertCount(1, $funds);
        $this->assertEquals('Test Fund 1', $funds[0]->name);
        $this->assertEquals(2000, $funds[0]->start_year);
        $this->assertEquals(1, $funds[0]->manager_id);
    }

    public function testGetAll_ReturnsEmptyArrayWhenNoMatchingFunds()
    {
        $manager = ManagerFactory::new()->create();

        $fund = Fund::factory()->create([
            'name' => 'Test Fund',
            'start_year' => 2000,
            'manager_id' => $manager->id,
        ]);

        $funds = $this->fundService->getAll('Non-existent Fund', $manager->id, 2000);

        $this->assertEmpty($funds);
    }

    public function testGetAll_ReturnsFundsForSpecificManager()
    {
        $manager1 = ManagerFactory::new()->create();
        $manager2 = ManagerFactory::new()->create();

        $fund1 = Fund::factory()->create([
            'name' => 'Test Fund 1',
            'start_year' => 2000,
            'manager_id' => $manager1->id,
        ]);

        $fund2 = Fund::factory()->create([
            'name' => 'Test Fund 2',
            'start_year' => 2001,
            'manager_id' => $manager2->id,
        ]);

        $funds = $this->fundService->getAll('Test Fund', $manager1->id, 2000);

        $this->assertCount(1, $funds);
        $this->assertEquals('Test Fund 1', $funds[0]->name);
        $this->assertEquals(2000, $funds[0]->start_year);
        $this->assertEquals($manager1->id, $funds[0]->manager_id);
    }    
}
