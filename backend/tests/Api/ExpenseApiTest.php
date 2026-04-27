<?php
namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpenseApiTest extends WebTestCase
{
    private function getAuthToken(object $client, string $email = "test@mybank.com"): string
    {
        $client->request(
            "POST",
            "/api/login",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            json_encode([
                "username" => $email,
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

    public function testPostOperationCreates201(): void
    {
        $client = static::createClient();
        $token = $this->getAuthToken($client);

        $client->request(
            "POST",
            "/api/operations",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer $token",
                "CONTENT_TYPE" => "application/json",
            ],
            json_encode([
                "label" => "Test purchase",
                "amount" => "50.00",
                "date" => "2026-01-15",
                "category" => 1,
            ]),
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testPostOperationWithoutRequiredFieldReturns422(): void
    {
        $client = static::createClient();
        $token = $this->getAuthToken($client);

        $client->request(
            "POST",
            "/api/operations",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer $token",
                "CONTENT_TYPE" => "application/json",
            ],
            json_encode([
                "label" => "",
            ]),
        );

        $this->assertResponseStatusCodeSame(422);
    }

    public function testPostRegisterReturns201(): void
    {
        $client = static::createClient();

        $client->request(
            "POST",
            "/api/register",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            json_encode([
                "email" => "newuser" . uniqid() . "@test.com",
                "password" => "password123",
            ]),
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testModifyOtherUserOperationReturns403(): void
    {
        $client = static::createClient();
        $tokenUser1 = $this->getAuthToken($client, "test@mybank.com");
        $tokenUser2 = $this->getAuthToken($client, "test2@mybank.com");

        $client->request(
            "PUT",
            "/api/operations/2",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer $tokenUser1",
                "CONTENT_TYPE" => "application/json",
            ],
            json_encode([
                "label" => "Hacked!",
            ]),
        );

        $this->assertResponseStatusCodeSame(403);
    }
}
