<?php

namespace Tests\Feature\Employee;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeMessageTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = User::create([
            'name' => 'Employee',
            'email' => 'emp@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'status' => 'active',
        ]);
    }

    private function makeMessage(array $overrides = []): Message
    {
        return Message::create(array_merge([
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'A question',
            'message' => 'Hello, I have a question about volunteering.',
            'is_read' => false,
        ], $overrides));
    }

    public function test_index_lists_messages_with_unread_badge(): void
    {
        $this->makeMessage(['subject' => 'Unread One']);
        $this->makeMessage(['subject' => 'Read One', 'is_read' => true]);

        $this->actingAs($this->employee)
            ->get(route('employee.messages.index'))
            ->assertOk()
            ->assertSee('Unread One')
            ->assertSee('1 غير مقروءة');
    }

    public function test_index_filters_unread_messages(): void
    {
        $this->makeMessage(['subject' => 'Unread']);
        $this->makeMessage(['subject' => 'Read', 'is_read' => true]);

        $this->actingAs($this->employee)
            ->get(route('employee.messages.index', ['filter' => 'unread']))
            ->assertOk()
            ->assertSee('Unread')
            ->assertDontSee('>Read<');
    }

    public function test_opening_a_message_marks_it_read(): void
    {
        $message = $this->makeMessage();

        $this->actingAs($this->employee)
            ->get(route('employee.messages.show', $message))
            ->assertOk()
            ->assertSee('Hello, I have a question about volunteering.');

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'is_read' => 1,
        ]);
    }

    public function test_employee_can_mark_a_message_read_from_list(): void
    {
        $message = $this->makeMessage();

        $this->actingAs($this->employee)
            ->patch(route('employee.messages.read', $message))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'is_read' => 1,
        ]);
    }

    public function test_employee_cannot_delete_a_message(): void
    {
        $message = $this->makeMessage();

        $response = $this->actingAs($this->employee)
            ->delete('employee/messages/' . $message->id);

        $this->assertTrue(
            in_array($response->getStatusCode(), [404, 405]),
            'Expected 404/405 but got ' . $response->getStatusCode()
        );

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
        ]);
    }
}