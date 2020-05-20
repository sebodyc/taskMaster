<?php

namespace App\DataFixtures;

use App\Entity\User;
use function foo\func;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends AbstractFixture
{
    protected $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 10, function (User $user, $i) {

            $user->setEmail("user$i@gmail.com")
                ->setFullname($this->faker->name())
                ->setAvatar("http://placehold.it/400x400")
                ->setPassword($this->encoder->encodePassword($user, "password"));
        });
    }
}
