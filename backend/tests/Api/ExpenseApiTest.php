<?php

namespace App\Tests\Api;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class ExpenseApiTest extends WebTestCase
{
    // - TEST 1 : GET /api/expenses ──────────────────────────────────
    public function testGetOperationsReturns200(): void
    {
        $client = static::createClient();
        $client->request("GET", "/api/operations");
        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }
    // ─- TEST 2 : POST /api/operations - cas nominal ───────────────────
    public function testPostOperationCreatesOperation(): void
    {
        $client = static::createClient();
        $client->request(
            "POST",
            "/api/operations",
            [],
            [],
            ["CONTENT_TYPE" => "application/ld+json"],
            json_encode([
                "label" => "Loyer",
                "amount" => "900.00",
                "date" => "2025-01-01T00:00:00+00:00",
                "category" => "/api/categories/1",
                "user" => "/api/users/1",
            ]),
        );
        $this->assertResponseStatusCodeSame(201);
    }
    // ─- TEST 3 : POST sans label - cas d'erreur ─────────────────────
    public function testPostOperationWithoutLabelReturns422(): void
    {
        $client = static::createClient();
        $client->request(
            "POST",
            "/api/operations",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            json_encode(["amount" => 50.0, "date" => "2025-01-01"]),
        );
        $this->assertResponseStatusCodeSame(422);
    }
    // ─- TEST 4 : GET dépense inexistante - 404 ──────────────────────
    public function testGetNonExistentOperationReturns404(): void
    {
        $client = static::createClient();
        $client->request("GET", "/api/operations/99999");
        $this->assertResponseStatusCodeSame(404);
    }
}
