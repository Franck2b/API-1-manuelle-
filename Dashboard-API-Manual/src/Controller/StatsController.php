<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\RevenueRepository;
use App\Repository\SubscriptionRepository;
use App\Service\ApiResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/stats', name: 'api_stats_')]
class StatsController extends AbstractController
{
    public function __construct(
        private RevenueRepository $revenueRepository,
        private OrderRepository $orderRepository,
        private SubscriptionRepository $subscriptionRepository,
        private ApiResponseService $apiResponse
    ) {
    }

    #[Route('/revenue', name: 'revenue', methods: ['GET'])]
    public function revenue(Request $request): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        $queryBuilder = $this->revenueRepository->createQueryBuilder('r');

        if ($startDate) {
            $queryBuilder->andWhere('r.date >= :startDate')
                ->setParameter('startDate', new \DateTime($startDate));
        }

        if ($endDate) {
            $queryBuilder->andWhere('r.date <= :endDate')
                ->setParameter('endDate', new \DateTime($endDate));
        }

        $revenues = $queryBuilder->getQuery()->getResult();

        // Calculate total revenue
        $totalRevenue = 0;
        foreach ($revenues as $revenue) {
            $totalRevenue += (float) $revenue->getAmount();
        }

        // Calculate comparison (last week vs current week)
        $now = new \DateTime();
        $lastWeekStart = clone $now;
        $lastWeekStart->modify('-14 days');
        $lastWeekEnd = clone $now;
        $lastWeekEnd->modify('-7 days');
        $currentWeekStart = clone $now;
        $currentWeekStart->modify('-7 days');

        $lastWeekQuery = $this->revenueRepository->createQueryBuilder('r')
            ->where('r.date >= :start AND r.date < :end')
            ->setParameter('start', $lastWeekStart)
            ->setParameter('end', $lastWeekEnd)
            ->getQuery()
            ->getResult();

        $currentWeekQuery = $this->revenueRepository->createQueryBuilder('r')
            ->where('r.date >= :start')
            ->setParameter('start', $currentWeekStart)
            ->getQuery()
            ->getResult();

        $lastWeekTotal = 0;
        foreach ($lastWeekQuery as $rev) {
            $lastWeekTotal += (float) $rev->getAmount();
        }

        $currentWeekTotal = 0;
        foreach ($currentWeekQuery as $rev) {
            $currentWeekTotal += (float) $rev->getAmount();
        }

        $percentageChange = $lastWeekTotal > 0 
            ? (($currentWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100 
            : 0;

        return $this->apiResponse->success([
            'total' => number_format($totalRevenue, 2, '.', ''),
            'percentageChange' => round($percentageChange, 2),
            'revenues' => $revenues,
        ], ['revenue:read']);
    }

    #[Route('/orders', name: 'orders', methods: ['GET'])]
    public function orders(Request $request): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        $queryBuilder = $this->orderRepository->createQueryBuilder('o');

        if ($startDate) {
            $queryBuilder->andWhere('o.orderDate >= :startDate')
                ->setParameter('startDate', new \DateTime($startDate));
        }

        if ($endDate) {
            $queryBuilder->andWhere('o.orderDate <= :endDate')
                ->setParameter('endDate', new \DateTime($endDate));
        }

        $orders = $queryBuilder->getQuery()->getResult();

        // Calculate stats
        $totalOrders = count($orders);
        $totalAmount = 0;
        foreach ($orders as $order) {
            $totalAmount += (float) $order->getAmount();
        }
        $avgOrderRevenue = $totalOrders > 0 ? $totalAmount / $totalOrders : 0;

        // Calculate comparison (last week)
        $now = new \DateTime();
        $lastWeekStart = clone $now;
        $lastWeekStart->modify('-14 days');
        $lastWeekEnd = clone $now;
        $lastWeekEnd->modify('-7 days');
        $currentWeekStart = clone $now;
        $currentWeekStart->modify('-7 days');

        $lastWeekOrders = $this->orderRepository->createQueryBuilder('o')
            ->where('o.orderDate >= :start AND o.orderDate < :end')
            ->setParameter('start', $lastWeekStart)
            ->setParameter('end', $lastWeekEnd)
            ->getQuery()
            ->getResult();

        $currentWeekOrders = $this->orderRepository->createQueryBuilder('o')
            ->where('o.orderDate >= :start')
            ->setParameter('start', $currentWeekStart)
            ->getQuery()
            ->getResult();

        $lastWeekCount = count($lastWeekOrders);
        $currentWeekCount = count($currentWeekOrders);

        $percentageChange = $lastWeekCount > 0 
            ? (($currentWeekCount - $lastWeekCount) / $lastWeekCount) * 100 
            : 0;

        return $this->apiResponse->success([
            'total' => $totalOrders,
            'avgOrderRevenue' => number_format($avgOrderRevenue, 2, '.', ''),
            'totalAmount' => number_format($totalAmount, 2, '.', ''),
            'percentageChange' => round($percentageChange, 2),
            'orders' => $orders,
        ], ['order:read']);
    }

    #[Route('/subscriptions', name: 'subscriptions', methods: ['GET'])]
    public function subscriptions(Request $request): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        $queryBuilder = $this->subscriptionRepository->createQueryBuilder('s');

        if ($startDate) {
            $queryBuilder->andWhere('s.subscriptionDate >= :startDate')
                ->setParameter('startDate', new \DateTime($startDate));
        }

        if ($endDate) {
            $queryBuilder->andWhere('s.subscriptionDate <= :endDate')
                ->setParameter('endDate', new \DateTime($endDate));
        }

        $subscriptions = $queryBuilder->getQuery()->getResult();

        // Calculate stats
        $totalSubscriptions = count($subscriptions);

        // Calculate comparison (last week)
        $now = new \DateTime();
        $lastWeekStart = clone $now;
        $lastWeekStart->modify('-14 days');
        $lastWeekEnd = clone $now;
        $lastWeekEnd->modify('-7 days');
        $currentWeekStart = clone $now;
        $currentWeekStart->modify('-7 days');

        $lastWeekSubs = $this->subscriptionRepository->createQueryBuilder('s')
            ->where('s.subscriptionDate >= :start AND s.subscriptionDate < :end')
            ->setParameter('start', $lastWeekStart)
            ->setParameter('end', $lastWeekEnd)
            ->getQuery()
            ->getResult();

        $currentWeekSubs = $this->subscriptionRepository->createQueryBuilder('s')
            ->where('s.subscriptionDate >= :start')
            ->setParameter('start', $currentWeekStart)
            ->getQuery()
            ->getResult();

        $lastWeekCount = count($lastWeekSubs);
        $currentWeekCount = count($currentWeekSubs);

        $percentageChange = $lastWeekCount > 0 
            ? (($currentWeekCount - $lastWeekCount) / $lastWeekCount) * 100 
            : 0;

        return $this->apiResponse->success([
            'total' => $totalSubscriptions,
            'percentageChange' => round($percentageChange, 2),
            'subscriptions' => $subscriptions,
        ], ['subscription:read']);
    }

    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(Request $request): Response
    {
        // Get all stats in one call
        $revenueResult = $this->revenue($request);
        $ordersResult = $this->orders($request);
        $subscriptionsResult = $this->subscriptions($request);

        $revenueData = json_decode($revenueResult->getContent(), true);
        $ordersData = json_decode($ordersResult->getContent(), true);
        $subscriptionsData = json_decode($subscriptionsResult->getContent(), true);

        return $this->apiResponse->success([
            'revenue' => $revenueData,
            'orders' => $ordersData,
            'subscriptions' => $subscriptionsData,
        ]);
    }
}

