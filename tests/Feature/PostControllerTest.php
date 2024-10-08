<?php

use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
uses(RefreshDatabase::class);
// covers(PostController::class);
mutates(PostController::class);

test('can get posts index', function () {
    $user = User::factory()->create();
    Post::factory()->count(3)->create();
    $response = $this->actingAs($user)->get(route('posts.index'));
    
    $response->assertOk();
    $response->assertViewIs('posts.index');
});

test('can get post create', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('posts.create'));
    $response->assertOk();
    $response->assertViewIs('posts.create');
});

test('can store post', function () {
    $user = User::factory()->create();
    Post::factory()->count(3)->create();
    expect(Post::count())->toBe(3);

    $response = $this->actingAs($user)->post(route('posts.store'), [
        'title' => 'post title',
        'content' => 'post content',
    ]);

    expect(Post::count())->toBe(4);
    $response->assertRedirect(route('posts.index'));
});

test('can get post show', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $response = $this->actingAs($user)->get(route('posts.show', $post));
    
    $response->assertOk();
    $response->assertViewIs('posts.show');
});

test('can get post edit page', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $response = $this->actingAs($user)->get(route('posts.edit', $post));
    $response->assertOk();
    $response->assertViewIs('posts.edit');
});

test('can update post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create([
        'title' => 'old title',
        'content' => 'old content',
    ]);
    
    $response = $this->actingAs($user)->patch(route('posts.update', $post), [
        'title' => 'new title',
        'content' => 'new content',
    ]);
    
    $post->refresh();
    expect($post->title)->toBe('new title');
    expect($post->content)->toBe('new content');
    $response->assertRedirect(route('posts.show', $post));
});

test('can delete post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    expect(Post::count())->toBe(1);
    
    $response = $this->actingAs($user)->delete(route('posts.destroy', $post));
    
    expect(Post::count())->toBe(0);
    $response->assertRedirect(route('posts.index'));
});