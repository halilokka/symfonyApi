<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Repository\UserRepository;

class AuthController extends AbstractController
{
    private $userRepository;

    public function __construct(userRepository $userRepository, EntityManagerInterface $_user)
    {
        $this->userRepository = $userRepository;
        $this->_user = $_user;
    }
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request, JWTTokenManagerInterface $JWTManager)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        if (empty($password) || empty($email)) {
            return new JsonResponse(['error' => 'Email veya Password eksik.'],Response::HTTP_OK);
        }
        
        
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            return new JsonResponse(['error' => 'Email kayıtlı değil.'], Response::HTTP_OK);
        }else{
            if($user->getPassword()!=$password){
                return new JsonResponse(['error' => 'Şifre yanlış.'], Response::HTTP_OK);
            }
        }

        $token = $JWTManager->create($user);

        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }
    /**
     * @Route("/login_check", name="login ckeck", methods={"GET"})
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function loginCheck(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }
}
