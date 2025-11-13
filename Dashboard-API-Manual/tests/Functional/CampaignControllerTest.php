<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CampaignControllerTest extends WebTestCase
{
    private $client;
    private $token;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        
        // Login with existing user from fixtures
        $this->client->request('POST', '/api/auth/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]));

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        $this->token = $data['token'] ?? null;
    }

    public function testGetCampaignsList(): void
    {
        $this->client->request('GET', '/api/campaigns', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
        
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        
        $this->assertArrayHasKey('items', $data);
        $this->assertArrayHasKey('pagination', $data);
    }

    public function testCreateCampaignValid(): void
    {
        $this->client->request('POST', '/api/campaigns', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
        ], json_encode([
            'platform' => 'facebook',
            'title' => 'Test Campaign',
            'status' => 'draft',
        ]));

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('facebook', $data['platform']);
        $this->assertEquals('Test Campaign', $data['title']);
    }

    public function testCreateCampaignInvalid(): void
    {
        $this->client->request('POST', '/api/campaigns', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
        ], json_encode([
            'platform' => 'invalid_platform',
            'title' => '', // Empty title should fail validation
            'status' => 'draft',
        ]));

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        
        $this->assertArrayHasKey('error', $data);
        $this->assertArrayHasKey('errors', $data);
    }
}

