<?php
namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExpenseApiTest extends WebTestCase
{
    private function getAuthToken(object $client, string $email = "test@mybank.com"): string
    {
        $container = $this->getContainer();
        $userRepository = $container->get('doctrine')->getRepository(\App\Entity\User::class);
        $jwtManager = $container->get('lexik_jwt_authentication.jwt_manager');
        
        $user = $userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            throw new \RuntimeException("User $email not found");
        }
        
        return $jwtManager->create($user);
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
            "PUT",
            "/api/operations/99999",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer $token",
                "CONTENT_TYPE" => "application/json",
            ],
            json_encode([
                "label" => "Updated",
            ]),
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

    public function testPostOperationWithoutRequiredFieldReturns400(): void
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

        $this->assertResponseStatusCodeSame(400);
    }

    public function testPostRegisterReturns201(): void
    {
        $client = static::createClient();

        $client->request(
            "POST",
            "/api/auth/register",
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

        $tokenUser2 = $this->getAuthToken($client, "test2@mybank.com");
        $tokenUser1 = $this->getAuthToken($client, "test@mybank.com");

        $client->request(
            "PUT",
            "/api/operations/1",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer $tokenUser2",
                "CONTENT_TYPE" => "application/json",
            ],
            json_encode([
                "label" => "Hacked!",
            ]),
        );

        $this->assertResponseStatusCodeSame(403);
    }
}
