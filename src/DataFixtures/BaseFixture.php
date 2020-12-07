<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

abstract class BaseFixture extends Fixture
{
    const MAX_USER_INDEX = 1;
    const MAX_TRICK_INDEX = 4;
}