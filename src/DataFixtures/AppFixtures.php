<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('fr_FR');

        $user = new User();
        $user->setName('Coline');
        $user->setEmail('bodartcoline@gmail.com');
        $user->setBirthAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
        $user->setPseudo('Cok');
        $user->setBiographie('');
        $user->setAvatar('C:\Users\bodar\Downloads\AvatarMaker_00d86.png');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $review = new Commentaire();
            $review->setContent($faker->text());
            $review->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $review->setUser($user);
            $manager->persist($review);
        }

        $manager->flush();
    }
}