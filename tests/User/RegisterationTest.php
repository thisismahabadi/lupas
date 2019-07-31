<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * @todo Add backend error test and registeration validation method
 */
class RegisterationTest extends TestCase
{
    /**
     * Register user successfully.
     *
     * @return void
     */
    public function testSuccessfullUserRegisteration()
    {
        $user = factory('App\Models\User\User')->make();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ];

        $this->json('POST', '/api/v1/register', $data)
             ->seeJson([
                'status' => 'success',
                'code' => 201,
             ]);
    }

    /**
     * Register user with duplicate email to get error.
     *
     * @return void
     */
    public function testUserRegisterationWithDuplicateError()
    {
        $user = factory('App\Models\User\User')->create();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ];

        $this->json('POST', '/api/v1/register', $data)
             ->seeJson([
                'status' => 'error',
                'code' => 422,
             ]);
    }
}