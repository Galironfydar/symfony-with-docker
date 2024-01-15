<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ApiKey;

class ApiKeyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $apiKey = new ApiKey();
        $apiKey->setApiKey('valid-api-key');

        $manager->persist($apiKey);
        $manager->flush();
    }
}
