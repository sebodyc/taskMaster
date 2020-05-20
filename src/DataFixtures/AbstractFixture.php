<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class AbstractFixture extends Fixture
{

    protected Generator $faker;
    protected ObjectManager $manager;


    protected function createMany(string $className, int $count, callable $callback)
    {

        for ($i = 0; $i < $count; $i++) {

            $object = new $className();

            $callback($object,$i);

            $this->manager->persist($object);

            $this->addReference($className . '_' . $i, $object);
        }

        $this->manager->flush();
    }


    final public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');
        $this->faker->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider($this->faker));
        $this->customizeFaker();
        $this->loadData($manager);
    }

    protected function customizeFaker(): void
    {
    }

    abstract protected function loadData(ObjectManager $manager);

    protected function getRandomReference(string $className)
    {

        $references = $this->referenceRepository->getReferences();

        $names = array_keys($references);

        $filteredNames = array_filter($names, function ($name) use ($className) {

            return strpos($name, $className . '_') === 0;
        });

        if (count($filteredNames) === 0) {
            throw new \Exception(sprintf("aucune ref pour la class %s,", $className));
        }

        $randomReferenceName = $this->faker->randomElement($filteredNames);

        return $this->getReference($randomReferenceName);
    }
}
