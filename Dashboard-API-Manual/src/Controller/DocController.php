<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/doc', name: 'api_doc_')]
class DocController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard API - Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui.css" />
    <style>
        html { box-sizing: border-box; overflow: -moz-scrollbars-vertical; overflow-y: scroll; }
        *, *:before, *:after { box-sizing: inherit; }
        body { margin:0; background: #fafafa; }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "/api/doc/json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            });
        };
    </script>
</body>
</html>
HTML;

        return new Response($html);
    }

    #[Route('/json', name: 'json', methods: ['GET'])]
    public function getJson(): JsonResponse
    {
        $doc = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Dashboard API Manual',
                'description' => 'API REST manuelle pour le Dashboard Analytics',
                'version' => '1.0.0',
            ],
            'servers' => [
                ['url' => 'http://localhost:8000', 'description' => 'Serveur de développement'],
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                    ],
                ],
            ],
            'security' => [['bearerAuth' => []]],
            'paths' => [
                '/api/auth/login' => [
                    'post' => [
                        'tags' => ['Auth'],
                        'summary' => 'Connexion',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['email', 'password'],
                                        'properties' => [
                                            'email' => ['type' => 'string'],
                                            'password' => ['type' => 'string'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Token JWT'],
                            '401' => ['description' => 'Identifiants invalides'],
                        ],
                    ],
                ],
                '/api/campaigns' => [
                    'get' => [
                        'tags' => ['Campaigns'],
                        'summary' => 'Liste des campagnes',
                        'parameters' => [
                            ['name' => 'page', 'in' => 'query', 'schema' => ['type' => 'integer', 'default' => 1]],
                            ['name' => 'limit', 'in' => 'query', 'schema' => ['type' => 'integer', 'default' => 10]],
                            ['name' => 'status', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['draft', 'in_progress', 'archived']]],
                            ['name' => 'platform', 'in' => 'query', 'schema' => ['type' => 'string']],
                            ['name' => 'search', 'in' => 'query', 'schema' => ['type' => 'string']],
                            ['name' => 'sort', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['id', 'title', 'status', 'platform', 'lastUpdated', 'createdAt']]],
                            ['name' => 'order', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['ASC', 'DESC']]],
                        ],
                        'responses' => ['200' => ['description' => 'Liste des campagnes']],
                        'security' => [['bearerAuth' => []]],
                    ],
                    'post' => [
                        'tags' => ['Campaigns'],
                        'summary' => 'Créer une campagne',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['platform', 'title', 'status'],
                                        'properties' => [
                                            'platform' => ['type' => 'string', 'enum' => ['facebook', 'instagram', 'google', 'twitter', 'linkedin']],
                                            'title' => ['type' => 'string'],
                                            'status' => ['type' => 'string', 'enum' => ['draft', 'in_progress', 'archived']],
                                            'startDate' => ['type' => 'string', 'format' => 'date'],
                                            'endDate' => ['type' => 'string', 'format' => 'date'],
                                            'progress' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 100],
                                            'collaborators' => ['type' => 'array', 'items' => ['type' => 'integer']],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => ['description' => 'Campagne créée'],
                            '422' => ['description' => 'Erreur de validation'],
                        ],
                        'security' => [['bearerAuth' => []]],
                    ],
                ],
                '/api/campaigns/{id}' => [
                    'get' => [
                        'tags' => ['Campaigns'],
                        'summary' => 'Détails d\'une campagne',
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Détails de la campagne'],
                            '404' => ['description' => 'Campagne non trouvée'],
                        ],
                        'security' => [['bearerAuth' => []]],
                    ],
                    'put' => [
                        'tags' => ['Campaigns'],
                        'summary' => 'Modifier une campagne',
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                        ],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'platform' => ['type' => 'string'],
                                            'title' => ['type' => 'string'],
                                            'status' => ['type' => 'string'],
                                            'startDate' => ['type' => 'string', 'format' => 'date'],
                                            'endDate' => ['type' => 'string', 'format' => 'date'],
                                            'progress' => ['type' => 'integer'],
                                            'collaborators' => ['type' => 'array', 'items' => ['type' => 'integer']],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Campagne modifiée'],
                            '404' => ['description' => 'Campagne non trouvée'],
                            '422' => ['description' => 'Erreur de validation'],
                        ],
                        'security' => [['bearerAuth' => []]],
                    ],
                    'delete' => [
                        'tags' => ['Campaigns'],
                        'summary' => 'Supprimer une campagne',
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                        ],
                        'responses' => [
                            '204' => ['description' => 'Campagne supprimée'],
                            '404' => ['description' => 'Campagne non trouvée'],
                        ],
                        'security' => [['bearerAuth' => []]],
                    ],
                ],
                '/api/stats/revenue' => [
                    'get' => [
                        'tags' => ['Stats'],
                        'summary' => 'Statistiques de revenus',
                        'parameters' => [
                            ['name' => 'startDate', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
                            ['name' => 'endDate', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
                        ],
                        'responses' => ['200' => ['description' => 'Statistiques de revenus']],
                        'security' => [['bearerAuth' => []]],
                    ],
                ],
                '/api/stats/orders' => [
                    'get' => [
                        'tags' => ['Stats'],
                        'summary' => 'Statistiques des commandes',
                        'parameters' => [
                            ['name' => 'startDate', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
                            ['name' => 'endDate', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
                        ],
                        'responses' => ['200' => ['description' => 'Statistiques des commandes']],
                        'security' => [['bearerAuth' => []]],
                    ],
                ],
                '/api/stats/subscriptions' => [
                    'get' => [
                        'tags' => ['Stats'],
                        'summary' => 'Statistiques des abonnements',
                        'parameters' => [
                            ['name' => 'startDate', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
                            ['name' => 'endDate', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
                        ],
                        'responses' => ['200' => ['description' => 'Statistiques des abonnements']],
                        'security' => [['bearerAuth' => []]],
                    ],
                ],
                '/api/stats/dashboard' => [
                    'get' => [
                        'tags' => ['Stats'],
                        'summary' => 'Statistiques complètes du dashboard',
                        'responses' => ['200' => ['description' => 'Statistiques complètes']],
                        'security' => [['bearerAuth' => []]],
                    ],
                ],
            ],
        ];

        return new JsonResponse($doc);
    }
}

