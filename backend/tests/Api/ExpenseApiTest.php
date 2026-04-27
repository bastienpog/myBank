<?php
namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpenseApiTest extends WebTestCase
{
    private function getAuthToken(object $client): string
    {
        $client->request(
            "POST",
            "/api/login",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            json_encode([
                "username" => "test@mybank.com",
                "password" => "password",
            ]),
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        return $data["token"];
    }

    public function testGetOperationsReturns200(): void
    {
        $client = static::createClient();
        $token = $this->getAuthToken($client);

        $client->request(
            "GET",
            "/api/operations",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer $token",
            ],
        );

        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetNonExistentOperationReturns404(): void
    {
        $client = static::createClient();
        $token = $this->getAuthToken($client);

        $client->request(
            "GET",
            "/api/operations/99999",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer $token",
            ],
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function testAccessWithoutTokenReturns401(): void
    {
        $client = static::createClient();
        $client->request("GET", "/api/operations");
        $this->assertResponseStatusCodeSame(401);
    }
}
