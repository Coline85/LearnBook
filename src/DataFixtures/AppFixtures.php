<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('Coline');
        $user->setEmail('bodartcoline@gmail.com');
        $user->setPseudo('Cok');
        $user->setAvatar('C:\Users\bodar\Downloads\AvatarMaker_00d86.png');
        // $user->setBirthAt('1985-06-16');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $review = new Review();
            $review->setContent($faker->text());
            $review->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $review->setUser($user);
            $manager->persist($review);
        }

        $manager->flush();
    }
}