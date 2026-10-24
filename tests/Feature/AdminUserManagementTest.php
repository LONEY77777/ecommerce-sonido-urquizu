<?php

use App\Models\Order;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

it('lists all users with their order counts', function () {
    $customer = User::factory()->create(['name' => 'Nadia Rahman']);
    Order::factory()->count(2)->for($customer)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.users.index'))
        ->assertSuccessful()
        ->assertSee('Nadia Rahman')
        ->assertViewHas('users', fn ($users) => $users->firstWhere('id', $customer->id)->orders_count === 2);
});

it('searches users by name and email', function () {
    User::factory()->create(['name' => 'Nadia Rahman', 'email' => 'nadia@example.com']);
    User::factory()->create(['name' => 'Someone Else', 'email' => 'other@example.com']);

    $this->actingAs($this->admin);

    $this->get(route('admin.users.index', ['search' => 'Nadia']))
        ->assertSee('Nadia Rahman')
        ->assertDontSee('Someone Else');

    $this->get(route('admin.users.index', ['search' => 'other@example.com']))
        ->assertSee('Someone Else')
        ->assertDontSee('Nadia Rahman');
});

it('shows a user with their order history', function () {
    $customer = User::factory()->create(['name' => 'Nadia Rahman']);
    $order = Order::factory()->for($customer)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.users.show', $customer))
        ->assertSuccessful()
        ->assertSee('Nadia Rahman')
        ->assertSee($order->order_number);
});

it('deletes a customer account', function () {
    $customer = User::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.users.destroy', $customer))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('users', ['id' => $customer->id]);
});

it('removes a deleted customer’s orders along with the account', function () {
    $customer = User::factory()->create();
    $order = Order::factory()->for($customer)->create();

    $this->actingAs($this->admin)->delete(route('admin.users.destroy', $customer));

    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
});

it('refuses to delete another administrator', function () {
    $otherAdmin = User::factory()->admin()->create();

    $this->actingAs($this->admin)
        ->from(route('admin.users.index'))
        ->delete(route('admin.users.destroy', $otherAdmin))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $otherAdmin->id]);
});

it('refuses to delete your own account', function () {
    $this->actingAs($this->admin)
        ->from(route('admin.users.index'))
        ->delete(route('admin.users.destroy', $this->admin))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
});
