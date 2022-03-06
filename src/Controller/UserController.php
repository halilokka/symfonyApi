<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Repository\UserRepository;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(userRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users", name="get_users", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ];
        }

        return new JsonResponse(['users' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/user/{id}", name="get_user", methods={"GET"})
     */
    public function getUserr($id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ];

        return new JsonResponse(['user' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/user", name="post_user", methods={"POST"})
     */
    public function postUser(Request $request): JsonResponse
    {
        $data = $request->query->all();

        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        if (empty($name) || empty($email) || empty($password)) {
            throw new NotFoundHttpException('Zorunlu olan değerler boş bırakılamaz.');
        }

        $this->userRepository->insert($data);

        return new JsonResponse(['status' => 'Üye eklendi.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/user/{id}", name="put_user", methods={"PUT"})
     */
    public function putuser($id, Request $request): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        $data = $request->query->all();

        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        if (empty($name) || empty($email) || empty($password)) {
            throw new NotFoundHttpException('Zorunlu olan değerler boş bırakılamaz.');
        }

        $this->userRepository->update($user, $data);

        return new JsonResponse(['status' => 'Üye güncellendi.']);
    }

    /**
     * @Route("/user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser($id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        $this->userRepository->remove($user);

        return new JsonResponse(['status' => 'Üye silindi.']);
    }
}
