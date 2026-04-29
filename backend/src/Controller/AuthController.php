<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    #[Route('/api/auth/login', name: 'api_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        throw new \LogicException('This should never be reached.');
    }

    #[Route('/api/auth/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse([
                'error' => 'Les champs email et password sont obligatoires',
            ], 400);
        }

        $email = trim($data['email']);
        $password = $data['password'];

        if (strlen($password) < 6) {
            return new JsonResponse([
                'error' => 'Le mot de passe doit faire au moins 6 caractères',
            ], 400);
        }

        if ($userRepository->findOneBy(['email' => $email])) {
            return new JsonResponse([
                'error' => 'Cette adresse email est déjà utilisée',
            ], 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($hasher->hashPassword($user, $password));

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($errors));
            return new JsonResponse(['error' => implode(', ', $messages)], 400);
        }

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ],
        ], 201);
    }

    #[Route('/api/auth/me', name: 'api_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/api/auth/change-password', name: 'api_change_password', methods: ['POST'])]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
    ): JsonResponse {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (!isset($data['currentPassword']) || !isset($data['newPassword'])) {
            return new JsonResponse([
                'error' => 'Les champs currentPassword et newPassword sont obligatoires',
            ], 400);
        }

        if (!$hasher->isPasswordValid($user, $data['currentPassword'])) {
            return new JsonResponse([
                'error' => 'Mot de passe actuel incorrect',
            ], 400);
        }

        if (strlen($data['newPassword']) < 6) {
            return new JsonResponse([
                'error' => 'Le nouveau mot de passe doit faire au moins 6 caractères',
            ], 400);
        }

        $user->setPassword($hasher->hashPassword($user, $data['newPassword']));
        $em->flush();

        return new JsonResponse(['message' => 'Mot de passe mis à jour']);
    }
}
