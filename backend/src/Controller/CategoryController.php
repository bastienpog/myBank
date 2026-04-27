<?php
namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(CategoryRepository $repo): JsonResponse
    {
        $user = $this->getUser();
        $categories = $repo->findBy(['user' => $user]);

        return new JsonResponse(array_map(fn($cat) => [
            'id' => $cat->getId(),
            'title' => $cat->getTitle(),
        ], $categories));
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $repo,
        ValidatorInterface $validator,
    ): JsonResponse {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'])) {
            return new JsonResponse(['error' => 'Le champ title est obligatoire'], 400);
        }

        $existing = $repo->findOneBy(['user' => $user, 'title' => $data['title']]);
        if ($existing) {
            return new JsonResponse(['error' => 'Cette catégorie existe déjà'], 400);
        }

        $category = new Category();
        $category->setTitle($data['title']);
        $category->setUser($user);

        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($errors));
            return new JsonResponse(['error' => implode(', ', $messages)], 422);
        }

        $em->persist($category);
        $em->flush();

        return new JsonResponse([
            'id' => $category->getId(),
            'title' => $category->getTitle(),
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        CategoryRepository $repo,
        EntityManagerInterface $em,
    ): JsonResponse {
        $user = $this->getUser();
        $category = $repo->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Catégorie non trouvée'], 404);
        }

        if ($category->getUser() !== $user) {
            return new JsonResponse(['error' => 'Accès refusé'], 403);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $category->setTitle($data['title']);
        }

        $em->flush();

        return new JsonResponse([
            'id' => $category->getId(),
            'title' => $category->getTitle(),
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, CategoryRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        $category = $repo->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Catégorie non trouvée'], 404);
        }

        if ($category->getUser() !== $user) {
            return new JsonResponse(['error' => 'Accès refusé'], 403);
        }

        $em->remove($category);
        $em->flush();

        return new JsonResponse(null, 204);
    }
}