<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Type;
use App\Models\Company;
use App\Models\User;
use Database\Factories\EventFactory;
use Database\Factories\TypeFactory;
use Illuminate\Http\Response;
use App\Models\Event;
use Illuminate\Http\JsonResponse;


class EventControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use WithFaker;
    use RefreshDatabase;

    public function testCreateMethod()
    {
        $type = Type::factory()->create();

        $response = $this->get(route('events.create'));

        $response->assertOk();

        $response->assertViewIs('events.create');

        $response->assertViewHas('types', function ($viewTypes) use ($type) {
            return $viewTypes->contains($type);
        });

        $this->assertFalse(session()->has('selected_type'));
        $this->assertFalse(session()->has('selected_companies'));
    }
    public function testGetCompaniesByType()
    {
        $type = Type::factory()->create();

        $companiesWithType = Company::factory()->count(3)->create(['type_id' => $type->id]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.getCompaniesByType', ['type_id' => $type->id]));

        $response->assertOk();
        $response->assertJsonStructure(['companies']);

        $responseData = $response->json();

        $this->assertCount(count($companiesWithType), $responseData['companies']);

        foreach ($companiesWithType as $company) {
            $this->assertContains(['id' => $company->id, 'name' => $company->name, 'type_id' => $company->type_id], $responseData['companies']);
        }
    }
    public function testStoreValidEventData()
    {
        $eventData = EventFactory::new()->make()->toArray();

        $response = $this->post(route('events.store'), $eventData);

        $response->assertStatus(201);

        $response->assertJson($eventData);

        $this->assertDatabaseHas('events', $eventData);
    }

    public function testStoreInvalidEventData()
    {
        $eventData = EventFactory::new()->make(['name' => null])->toArray();

        $response = $this->postJson(route('events.store'), $eventData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name']);

        $this->assertDatabaseMissing('events', $eventData);
    }
    public function testSelectCompaniesTypeIdMissing()
    {
        $response = $this->postJson(route('events.selectCompanies'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('type_id');
    }

    public function testSelectCompaniesTypeIdNotExists()
    {
        $nonExistentTypeId = 9999;

        $response = $this->postJson(route('events.selectCompanies'), [
            'type_id' => $nonExistentTypeId,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('type_id');
    }

    public function testSelectCompaniesSuccess()
    {
        $type = TypeFactory::new()->create();
        $selectedTypeId = $type->id;

        $response = $this->postJson(route('events.selectCompanies'), [
            'type_id' => $selectedTypeId,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertEquals($selectedTypeId, session('selected_type'));

        $this->assertFalse(session()->has('selected_companies'));

        $response->assertRedirect(route('events.chooseCompanyAndDate'));
    }
    public function testChooseCompanyAndDateWithoutSelectedType()
    {
        $response = $this->get(route('events.chooseCompanyAndDate'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('events.create'))
            ->assertSessionHasErrors('error', 'Please select a type first.');
    }

    public function testChooseCompanyAndDateWithSelectedType()
    {
        $type = Type::factory()->create();
        $companies = Company::factory()->count(3)->create(['type_id' => $type->id]);

        session(['selected_type' => $type->id]);

        $response = $this->get(route('events.chooseCompanyAndDate'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('events.choose_company_and_date')
            ->assertViewHas('companies', $companies);
    }
    public function testUpdateEventSuccessfully()
    {
        $event = Event::factory()->create();
        $type1 = Type::factory()->create();
        $company1 = Company::factory()->create(['type_id' => $type1->id]);

        $updateData = [
            'type_id' => $type1->id,
            'name' => 'Updated Event Name',
            'date' => '2023-07-25',
            'status' => 'pending',
            'data' => [
                ['type_id' => $type1->id, 'company_id' => $company1->id],
                // You can add more companies here if needed
            ],
        ];

        $response = $this->putJson(route('events.update', ['id' => $event->id]), $updateData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'name' => 'Updated Event Name',
                'date' => '2023-07-25',
                'status' => 'pending',
            ]);

        // Optionally, you can assert the updated data in the database as well
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Updated Event Name',
            'date' => '2023-07-25',
            'status' => 'pending',
        ]);
    }


    public function testUpdateEventWithInvalidData()
    {
        $event = Event::factory()->create();

        $type1 = Type::factory()->create();
        $company1 = Company::factory()->create(['type_id' => $type1->id]);

        $eventData = [
            'type_id' => $type1->id,
            // 'name' field is missing here
            'date' => $this->faker->date, // Using factory to generate random date
            'status' => 'pending',
            'data' => [
                ['type_id' => $type1->id, 'company_id' => $company1->id],
            ],
        ];

        $response = $this->putJson(route('events.update', ['id' => $event->id]), $eventData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }
    public function testDestroyEvent()
    {
        $event = Event::factory()->create();

        $response = $this->deleteJson(route('events.destroy', ['id' => $event->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Event deleted successfully']);

        $this->assertNull(Event::find($event->id));
    }

}
