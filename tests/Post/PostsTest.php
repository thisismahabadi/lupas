<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PostsTest extends TestCase
{
    /**
     * Visit posts page as logged-in user successfully.
     *
     * @return void
     */
    public function testVisitPostsAsLoggedInUser()
    {
        $user = factory('App\Models\User\User')->create();

        $this->actingAs($user)
             ->get('/api/v1/posts')
             ->seeJson([
                'status' => 'success',
                'code' => 200,
             ]);
    }

    /**
     * Visit posts page as guest successfully.
     *
     * @return void
     */
    public function testVisitPostsAsGuest()
    {
        $this->get('/api/v1/posts')
             ->seeJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }

    /**
     * Create posts successfully.
     *
     * @return void
     */
    public function testCreatePostSuccessfully()
    {
        $post = factory('App\Models\Post\Post')->make();
        $user = factory('App\Models\User\User')->create();

        $data = [
            'title' => $post->title,
            'description' => $post->description,
        ];

        $this->actingAs($user)
             ->json('POST', '/api/v1/posts', $data)
             ->seeJson([
                'status' => 'success',
                'code' => 201,
             ]);
    }

    /**
     * Create posts with error.
     *
     * @return void
     */
    public function testCreatePostWithError()
    {
        $post = factory('App\Models\Post\Post')->make();
        $user = factory('App\Models\User\User')->create();

        $data = [
            'title' => $post->title,
        ];

        $this->actingAs($user)
             ->json('POST', '/api/v1/posts', $data)
             ->seeJson([
                'status' => 'error',
                'code' => 422,
             ]);
    }

    /**
     * Create posts as guest.
     *
     * @return void
     */
    public function testCreatePostAsGuest()
    {
        $post = factory('App\Models\Post\Post')->make();

        $data = [
            'title' => $post->title,
            'description' => $post->description,
        ];

        $this->json('POST', '/api/v1/posts', $data)
             ->seeJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }

    /**
     * Visit specific post.
     *
     * @return void
     */
    public function testVisitSpecificPost()
    {
        $user = factory('App\Models\User\User')->create();
        $post = factory('App\Models\Post\Post')->create();

        $this->actingAs($user)
             ->get('/api/v1/posts/'.$post->id)
             ->seeJson([
                'status' => 'success',
                'code' => 200,
             ]);
    }

    /**
     * Visit specific post as guest.
     *
     * @return void
     */
    public function testVisitSpecificPostAsGuest()
    {
        $post = factory('App\Models\Post\Post')->create();

        $this->get('/api/v1/posts/'.$post->id)
             ->seeJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }

    /**
     * Delete specific post.
     *
     * @return void
     */
    public function testDeleteSpecificPost()
    {
        $user = factory('App\Models\User\User')->create();
        $post = factory('App\Models\Post\Post')->create();

        $this->actingAs($user)
             ->json('DELETE', '/api/v1/posts/'.$post->id)
             ->assertResponseStatus(204);
    }

    /**
     * Delete specific post as guest.
     *
     * @return void
     */
    public function testDeleteSpecificPostAsGuest()
    {
        $post = factory('App\Models\Post\Post')->create();

        $this->json('DELETE', '/api/v1/posts/'.$post->id)
             ->seeJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }

    /**
     * Update specific post successfully.
     *
     * @return void
     */
    public function testUpdateSpecificPostSuccessfully()
    {
        $user = factory('App\Models\User\User')->create();
        $post = factory('App\Models\Post\Post')->create();

        $data = [
            'title' => $post->title,
            'description' => $post->description,
        ];

        $this->actingAs($user)
             ->json('PUT', '/api/v1/posts/'.$post->id, $data)
             ->seeJson([
                'status' => 'success',
                'code' => 200,
             ]);
    }

    /**
     * Update specific post with error.
     *
     * @return void
     */
    public function testUpdateSpecificPostWithError()
    {
        $user = factory('App\Models\User\User')->create();
        $post = factory('App\Models\Post\Post')->create();

        $data = [
            'title' => $post->title,
        ];

        $this->actingAs($user)
             ->json('PUT', '/api/v1/posts/'.$post->id, $data)
             ->seeJson([
                'status' => 'error',
                'code' => 422,
             ]);
    }

    /**
     * Update specific post as guest.
     *
     * @return void
     */
    public function testUpdateSpecificPostAsGuest()
    {
        $post = factory('App\Models\Post\Post')->create();

        $data = [
            'title' => $post->title,
            'description' => $post->description,
        ];

        $this->json('PUT', '/api/v1/posts/'.$post->id, $data)
             ->seeJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }
}