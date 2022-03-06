<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use Faker\Factory;

use App\Entity\User;
use App\Entity\Order;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $faker = Faker::create("tr_TR");

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setName("İsim ".($i+1));
            $user->setEmail("deneme". ($i + 1)."@deneme.com");
            $user->setPassword("Sifre". ($i + 1));
            $manager->persist($user);
        }

        for ($i = 0; $i < 5; $i++) {
            $order = new Order();
            $order->setUserId(($i + 1));
            $order->setOrderCode("Deneme-". ($i + 1));
            $order->setProductId("1");
            $order->setQuantity("1");
            $order->setAddress("Açık Adres ilçe / il");
            $manager->persist($order);
        }

        $manager->flush();
    }
}
