<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tasks;
use Illuminate\Support\Facades\Hash;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'mock@example.com',
            'password' => Hash::make('mockpassword'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'mock@example.com',
            'password' => 'mockpassword',
        ]);

        $this->token = $response->json('data.access_token');
    }

    /** @test */
    public function it_lists_tasks()
    {
        Tasks::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pendente',
        ]);

        $response = $this->getJson('/api/tasks', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'user_id',
                            'title',
                            'description',
                            'status',
                        ],
                    ],
                ]);
    }

    /** @test */
    public function it_creates_a_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'New Description',
            'status' => 'pendente',
        ];

        $response = $this->postJson('/api/tasks', $taskData, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'title' => 'New Task',
                        'description' => 'New Description',
                        'status' => 'pendente',
                    ],
                    'message' => 'Task Criada com Sucesso.',
                ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'description' => 'New Description',
            'status' => 'pendente',
        ]);
    }

    /** @test */
    public function it_edits_a_task()
    {
        $task = Tasks::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Old Task',
            'description' => 'Old Description',
            'status' => 'pendente',
        ]);

        $updatedData = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'status' => 'concluida',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [],
                    'message' => 'Task Alterada com Sucesso.',
                ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'status' => 'concluida',
        ]);
    }

    /** @test */
    public function it_removes_a_task()
    {
        $task = Tasks::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Task to be deleted',
            'description' => 'Description',
            'status' => 'pendente',
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}", [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Task Removida Com Sucesso.',
                ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}