<?php

namespace AwemaPL\Auth\Tests\Unit\Services;

use AwemaPL\Auth\Tests\TestCase;
use AwemaPL\Auth\Tests\Stubs\User;
use AwemaPL\Auth\Models\UserSocial;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class UserSocialTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    
    /** @test */
    public function it_belongs_to_user()
    {
        $user = factory(User::class)->create();

        $social = factory(UserSocial::class)->create([
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $social->user);
    }
}