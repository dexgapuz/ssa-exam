<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */

class UserServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {
        // Arrangements
        User::factory(10)->create();
        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);

        // Actions
        $list = $userService->list();


        // Assertions
        $this->assertInstanceOf(LengthAwarePaginator::class, $list);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        // Arrangements
        $data = [
            'prefixname' => $this->faker()->title(),
            'firstname' => $this->faker()->firstName(),
            'middlename' => $this->faker()->lastName(),
            'lastname' => $this->faker()->lastName(),
            'suffixname' => $this->faker()->suffix(),
            'email' => $this->faker()->safeEmail(),
            'username' => $this->faker()->userName(),
            'password' => $this->faker()->password()
        ];
        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);
        // Actions
        $model = $userService->store($data);

        // Assertions
        $this->assertDatabaseHas('users', ['username' => $data['username']]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
        // Arrangements
        $userFactory = User::factory(1)->create(['username' => 'test_username']);

        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);

        // Actions
        $userData = $userService->find(1);

        // Assertions
        $this->assertEquals($userData->username, 'test_username');
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        // Arrangements
        $data = [
            'prefixname' => $this->faker()->title(),
            'firstname' => $this->faker()->firstName(),
            'middlename' => $this->faker()->lastName(),
            'lastname' => $this->faker()->lastName(),
            'suffixname' => $this->faker()->suffix(),
            'email' => $this->faker()->safeEmail(),
            'username' => $this->faker()->userName(),
            'password' => $this->faker()->password()
        ];

        $userFactory = User::factory(1)->create(['username' => 'test_username']);
        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);

        // Actions
        $update = $userService->update(1, $data);

        // Assertions
        $this->assertTrue($update);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
        // Arrangements
        $userFactory = User::factory(1)->create(['username' => 'test_username']);
        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);

        // Actions
        $userService->destroy(1);

        // Assertions
        $this->assertSoftDeleted('users', ['username' => 'test_username']);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        // Arrangements
        $userFactory = User::factory(10)->create(['deleted_at' => now()]);
        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);

        // Actions
        $list = $userService->listTrashed(1);

        // Assertions
        $this->assertInstanceOf(LengthAwarePaginator::class, $list);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Arrangements
        $userFactory = User::factory(1)->create(['username' => 'test_username', 'deleted_at' => now()]);
        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);

        // Actions
        $data = User::onlyTrashed()->where('username', 'test_username')->first();
        $userService->restore($data->id);

        // Assertions
        $this->assertNotSoftDeleted('users', ['username' => 'test_username']);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        $userFactory = User::factory(1)->create(['username' => 'test_username', 'deleted_at' => now()]);
        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);

        // Actions
        $data = User::onlyTrashed()->where('username', 'test_username')->first();
        $userService->delete($data->id);

        // Assertions
        $this->assertDatabaseMissing('users', ['username' => 'test_username']);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {
        // Arrangements
        $file = UploadedFile::fake()->image('test.jpg');
        $user = new User();
        $request = new Request();
        $userService = new UserService($user, $request);

        // Actions
        $path = $userService->upload($file);

        // Assertions
        $this->assertTrue(Storage::disk('public')->exists($path));
    }
}
