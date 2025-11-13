<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiResponseService
{
    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    public function success($data, array $groups = [], int $statusCode = Response::HTTP_OK, bool $enableCache = true): JsonResponse
    {
        $context = [];
        if (!empty($groups)) {
            $context['groups'] = $groups;
        }

        $json = $this->serializer->serialize($data, 'json', $context);

        $response = new JsonResponse($json, $statusCode, [], true);

        // Ajouter Cache-Control et ETag pour améliorer les performances
        if ($enableCache) {
            // Cache-Control : indique au navigateur de mettre en cache pendant 60 secondes
            $response->setMaxAge(60);
            $response->setSharedMaxAge(60);
            
            // ETag : hash du contenu pour vérifier si les données ont changé
            $etag = md5($json);
            $response->setEtag($etag);
        }

        return $response;
    }

    public function created($data, array $groups = []): JsonResponse
    {
        // Pas de cache pour les créations (données fraîches)
        return $this->success($data, $groups, Response::HTTP_CREATED, false);
    }

    public function noContent(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function error(string $message, int $statusCode = Response::HTTP_BAD_REQUEST, array $errors = []): JsonResponse
    {
        $data = [
            'error' => $message,
            'status' => $statusCode,
        ];

        if (!empty($errors)) {
            $data['errors'] = $errors;
        }

        return new JsonResponse($data, $statusCode);
    }

    public function validationErrors(ConstraintViolationListInterface $violations): JsonResponse
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $this->error('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    public function notFound(string $resource = 'Resource'): JsonResponse
    {
        return $this->error($resource . ' not found', Response::HTTP_NOT_FOUND);
    }

    public function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, Response::HTTP_UNAUTHORIZED);
    }

    public function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, Response::HTTP_FORBIDDEN);
    }
}

