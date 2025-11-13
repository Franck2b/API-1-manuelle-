<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Repository\CampaignRepository;
use App\Service\ApiResponseService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/campaigns', name: 'api_campaigns_')]
class CampaignController extends AbstractController
{
    public function __construct(
        private CampaignRepository $campaignRepository,
        private EntityManagerInterface $entityManager,
        private ApiResponseService $apiResponse,
        private PaginationService $paginationService,
        private ValidatorInterface $validator
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $queryBuilder = $this->campaignRepository->createQueryBuilder('e');

        // Apply filters
        $allowedFilters = [
            'status' => 'status',
            'platform' => 'platform',
            'search' => ['title'], // Search in title
        ];
        $this->paginationService->applyFilters($queryBuilder, $request, $allowedFilters);

        // Apply sorting
        $allowedSorts = ['id', 'title', 'status', 'platform', 'lastUpdated', 'createdAt'];
        $this->paginationService->applySorting($queryBuilder, $request, $allowedSorts, 'lastUpdated', 'DESC');

        // Paginate
        $result = $this->paginationService->paginate($queryBuilder, $request, 10);

        $response = $this->apiResponse->success($result, ['campaign:read']);
        
        // Vérifier si les données ont changé avec ETag (optimisation performance)
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            return $this->apiResponse->notFound('Campaign');
        }

        return $this->apiResponse->success($campaign, ['campaign:read']);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $campaign = new Campaign();
        $campaign->setPlatform($data['platform'] ?? null);
        $campaign->setTitle($data['title'] ?? null);
        $campaign->setStatus($data['status'] ?? 'draft');
        $campaign->setProgress($data['progress'] ?? null);

        if (isset($data['startDate'])) {
            $campaign->setStartDate(new \DateTime($data['startDate']));
        }
        if (isset($data['endDate'])) {
            $campaign->setEndDate(new \DateTime($data['endDate']));
        }

        // Handle collaborators
        if (isset($data['collaborators']) && is_array($data['collaborators'])) {
            $userRepository = $this->entityManager->getRepository(\App\Entity\User::class);
            foreach ($data['collaborators'] as $collaboratorId) {
                $user = $userRepository->find($collaboratorId);
                if ($user) {
                    $campaign->addCollaborator($user);
                }
            }
        }

        $violations = $this->validator->validate($campaign);
        if (count($violations) > 0) {
            return $this->apiResponse->validationErrors($violations);
        }

        $this->entityManager->persist($campaign);
        $this->entityManager->flush();

        return $this->apiResponse->created($campaign, ['campaign:read']);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'], requirements: ['id' => '\d+'])]
    public function update(int $id, Request $request): Response
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            return $this->apiResponse->notFound('Campaign');
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['platform'])) {
            $campaign->setPlatform($data['platform']);
        }
        if (isset($data['title'])) {
            $campaign->setTitle($data['title']);
        }
        if (isset($data['status'])) {
            $campaign->setStatus($data['status']);
        }
        if (isset($data['progress'])) {
            $campaign->setProgress($data['progress']);
        }
        if (isset($data['startDate'])) {
            $campaign->setStartDate(new \DateTime($data['startDate']));
        }
        if (isset($data['endDate'])) {
            $campaign->setEndDate(new \DateTime($data['endDate']));
        }

        // Handle collaborators
        if (isset($data['collaborators']) && is_array($data['collaborators'])) {
            // Clear existing collaborators
            foreach ($campaign->getCollaborators() as $collaborator) {
                $campaign->removeCollaborator($collaborator);
            }

            // Add new collaborators
            $userRepository = $this->entityManager->getRepository(\App\Entity\User::class);
            foreach ($data['collaborators'] as $collaboratorId) {
                $user = $userRepository->find($collaboratorId);
                if ($user) {
                    $campaign->addCollaborator($user);
                }
            }
        }

        $campaign->setLastUpdated(new \DateTimeImmutable());

        $violations = $this->validator->validate($campaign);
        if (count($violations) > 0) {
            return $this->apiResponse->validationErrors($violations);
        }

        $this->entityManager->flush();

        return $this->apiResponse->success($campaign, ['campaign:read']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            return $this->apiResponse->notFound('Campaign');
        }

        $this->entityManager->remove($campaign);
        $this->entityManager->flush();

        return $this->apiResponse->noContent();
    }
}

