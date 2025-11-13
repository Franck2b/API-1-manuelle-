<?php

namespace App\DataFixtures;

use App\Entity\Campaign;
use App\Entity\Order;
use App\Entity\Revenue;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Create users
        $users = [];
        $userData = [
            ['email' => 'admin@example.com', 'firstName' => 'Amanda', 'lastName' => 'Davids', 'initials' => 'AD', 'color' => 'bg-pink-500', 'role' => 'Administrator'],
            ['email' => 'john@example.com', 'firstName' => 'John', 'lastName' => 'Doe', 'initials' => 'JD', 'color' => 'bg-blue-500', 'role' => 'Designer'],
            ['email' => 'sarah@example.com', 'firstName' => 'Sarah', 'lastName' => 'Miller', 'initials' => 'SM', 'color' => 'bg-green-500', 'role' => 'Marketing Manager'],
            ['email' => 'alex@example.com', 'firstName' => 'Alex', 'lastName' => 'Khan', 'initials' => 'AK', 'color' => 'bg-purple-500', 'role' => 'Product Manager'],
        ];

        foreach ($userData as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setInitials($data['initials']);
            $user->setColor($data['color']);
            $user->setRole($data['role']);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[] = $user;
        }

        // Create campaigns
        $campaigns = [
            [
                'platform' => 'facebook',
                'title' => '10 Simple steps to revolutionise workflows with our product',
                'status' => 'draft',
                'collaborators' => [0, 1, 2],
            ],
            [
                'platform' => 'instagram',
                'title' => '10 simple steps to revolutionise workflows with our product',
                'status' => 'draft',
                'collaborators' => [0, 1],
            ],
            [
                'platform' => 'google',
                'title' => 'Boots your performance: start using our amazing product',
                'status' => 'in_progress',
                'startDate' => new \DateTime('2023-06-01'),
                'endDate' => new \DateTime('2023-08-01'),
                'progress' => 65,
                'collaborators' => [0, 1],
            ],
            [
                'platform' => 'facebook',
                'title' => 'Boost your performance: start using our amazing product',
                'status' => 'in_progress',
                'startDate' => new \DateTime('2023-06-01'),
                'endDate' => new \DateTime('2023-08-01'),
                'progress' => 40,
                'collaborators' => [3],
            ],
            [
                'platform' => 'google',
                'title' => 'Boost your performance: start using our amazing product',
                'status' => 'archived',
                'endDate' => new \DateTime('2023-06-11'),
                'collaborators' => [0, 1, 2, 3],
            ],
        ];

        foreach ($campaigns as $data) {
            $campaign = new Campaign();
            $campaign->setPlatform($data['platform']);
            $campaign->setTitle($data['title']);
            $campaign->setStatus($data['status']);
            if (isset($data['startDate'])) {
                $campaign->setStartDate($data['startDate']);
            }
            if (isset($data['endDate'])) {
                $campaign->setEndDate($data['endDate']);
            }
            if (isset($data['progress'])) {
                $campaign->setProgress($data['progress']);
            }
            foreach ($data['collaborators'] as $index) {
                $campaign->addCollaborator($users[$index]);
            }
            $manager->persist($campaign);
        }

        // Create revenue 
        $revenue = new Revenue();
        $revenue->setAmount('55');
        $revenue->setDate(new \DateTime('2024-11-01'));
        $manager->persist($revenue);

        // Create orders
        $orders = [
            ['amount' => '1250', 'orderDate' => '2024-11-05', 'status' => 'completed'],
            ['amount' => '850', 'orderDate' => '2024-11-10', 'status' => 'pending'],
            ['amount' => '2100', 'orderDate' => '2024-11-15', 'status' => 'completed'],
            ['amount' => '650', 'orderDate' => '2024-11-20', 'status' => 'cancelled'],
            ['amount' => '1800', 'orderDate' => '2024-11-25', 'status' => 'completed'],
        ];

        foreach ($orders as $orderData) {
            $order = new Order();
            $order->setAmount($orderData['amount']);
            $order->setOrderDate(new \DateTime($orderData['orderDate']));
            $order->setStatus($orderData['status']);
            $manager->persist($order);
        }

        // Create subscriptions
        $subscriptions = [
            ['subscriptionDate' => '2024-10-15', 'plan' => 'pro', 'status' => 'active'],
            ['subscriptionDate' => '2024-10-20', 'plan' => 'basic', 'status' => 'active'],
            ['subscriptionDate' => '2024-10-25', 'plan' => 'enterprise', 'status' => 'active'],
            ['subscriptionDate' => '2024-11-01', 'plan' => 'pro', 'status' => 'cancelled'],
            ['subscriptionDate' => '2024-11-10', 'plan' => 'basic', 'status' => 'active'],
        ];

        foreach ($subscriptions as $subData) {
            $subscription = new Subscription();
            $subscription->setSubscriptionDate(new \DateTime($subData['subscriptionDate']));
            $subscription->setPlan($subData['plan']);
            $subscription->setStatus($subData['status']);
            $manager->persist($subscription);
        }

        $manager->flush();
    }
}

