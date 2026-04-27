<?php
namespace App\Controller;

use App\Entity\Operation;
use App\Entity\Category;
use App\Repository\OperationRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/operations')]
class OperationController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(OperationRepository $repo): JsonResponse
    {
        $user = $this->getUser();
        $operations = $repo->findBy(['user' => $user]);

        return new JsonResponse(array_map(fn($op) => [
            'id' => $op->getId(),
            'label' => $op->getLabel(),
            'amount' => $op->getAmount(),
            'date' => $op->getDate()->format('Y-m-d'),
            'category' => $op->getCategory()?->getTitle(),
        ], $operations));
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepo,
        ValidatorInterface $validator,
    ): JsonResponse {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (!isset($data['label']) || !isset($data['amount']) || !isset($data['date']) || !isset($data['category'])) {
            return new JsonResponse(['error' => 'Les champs label, amount, date et category sont obligatoires'], 400);
        }

        $category = $categoryRepo->find($data['category']);
        if (!$category || $category->getUser() !== $user) {
            return new JsonResponse(['error' => 'Catégorie invalide'], 400);
        }

        $operation = new Operation();
        $operation->setLabel($data['label']);
        $operation->setAmount($data['amount']);
        $operation->setDate(new \DateTime($data['date']));
        $operation->setCategory($category);
        $operation->setUser($user);

        $errors = $validator->validate($operation);
        if (count($errors) > 0) {
            $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($errors));
            return new JsonResponse(['error' => implode(', ', $messages)], 422);
        }

        $em->persist($operation);
        $em->flush();

        return new JsonResponse([
            'id' => $operation->getId(),
            'label' => $operation->getLabel(),
            'amount' => $operation->getAmount(),
            'date' => $operation->getDate()->format('Y-m-d'),
            'category' => $operation->getCategory()->getTitle(),
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        OperationRepository $repo,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepo,
    ): JsonResponse {
        $user = $this->getUser();
        $operation = $repo->find($id);

        if (!$operation || $operation->getUser() !== $user) {
            return new JsonResponse(['error' => 'Opération non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['label'])) {
            $operation->setLabel($data['label']);
        }
        if (isset($data['amount'])) {
            $operation->setAmount($data['amount']);
        }
        if (isset($data['date'])) {
            $operation->setDate(new \DateTime($data['date']));
        }
        if (isset($data['category'])) {
            $category = $categoryRepo->find($data['category']);
            if ($category && $category->getUser() === $user) {
                $operation->setCategory($category);
            }
        }

        $em->flush();

        return new JsonResponse([
            'id' => $operation->getId(),
            'label' => $operation->getLabel(),
            'amount' => $operation->getAmount(),
            'date' => $operation->getDate()->format('Y-m-d'),
            'category' => $operation->getCategory()->getTitle(),
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, OperationRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        $operation = $repo->find($id);

        if (!$operation || $operation->getUser() !== $user) {
            return new JsonResponse(['error' => 'Opération non trouvée'], 404);
        }

        $em->remove($operation);
        $em->flush();

        return new JsonResponse(null, 204);
    }
}