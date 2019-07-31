<?php

use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * @todo Add backend error test and login validation method
 */
class LoginTest extends TestCase
{
    /**
     * Login user successfully.
     *
     * @return void
     */
    public function testSuccessfullUserLogin()
    {
        $user = factory('App\Models\User\User')->states('unhash')->make();
        $password = $user->password;
        $user = factory('App\Models\User\User')->create([
                    'password' => Hash::make($password),
                ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->json('POST', '/api/v1/login', $data)
             ->seeJson([
                'status' => 'success',
                'code' => 200,
             ]);
    }

    /**
     * Login user with wrong enteries to get error.
     *
     * @return void
     */
    public function testUserLoginWithWrongEnteries()
    {
        $user = factory('App\Models\User\User')->make();

        $data = [
            'email' => $user->email,
            'password' => $user->password,
        ];

        $this->json('POST', '/api/v1/login', $data)
             ->seeJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }
}