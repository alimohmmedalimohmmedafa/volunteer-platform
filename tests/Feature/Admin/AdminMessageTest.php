<?php

namespace Tests\Feature\Admin;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMessageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    private function makeMessage(array $overrides = []): Message
    {
        return Message::create(array_merge([
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'phone' => '0911111111',
            'subject' => 'A question',
            'message' => 'Hello, I have a question about volunteering.',
            'is_read' => false,
        ], $overrides));
    }

    public function test_index_lists_messages(): void
    {
        $message = $this->makeMessage(['subject' => 'A question']);

        $this->actingAs($this->admin)
            ->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('A question')
            ->assertSee('Visitor');
    }

    public function test_index_shows_unread_count_and_filter(): void
    {
        $this->makeMessage(['subject' => 'Unread One']);
        $this->makeMessage(['subject' => 'Unread Two']);
        $this->makeMessage(['subject' => 'Read One', 'is_read' => true]);

        $this->actingAs($this->admin)
            ->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('2 غير مقروءة');

        $this->actingAs($this->admin)
            ->get(route('admin.messages.index', ['filter' => 'unread']))
            ->assertOk()
            ->assertSee('Unread One')
            ->assertDontSee('Read One');
    }

    public function test_opening_a_message_marks_it_read(): void
    {
        $message = $this->makeMessage();

        $this->actingAs($this->admin)
            ->get(route('admin.messages.show', $message))
            ->assertOk()
            ->assertSee('Hello, I have a question about volunteering.');

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'is_read' => 1,
        ]);
    }

    public function test_admin_can_mark_a_message_read_from_list(): void
    {
        $message = $this->makeMessage();

        $this->actingAs($this->admin)
            ->patch(route('admin.messages.read', $message))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'is_read' => 1,
        ]);
    }

    public function test_sidebar_badge_shows_unread_messages(): void
    {
        $this->makeMessage(['subject' => 'Unread']);

        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('1', false)
            ->assertSee('الرسائل');
    }
}