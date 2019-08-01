<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

class LoginController extends AbstractFOSRestController
{
    /**
     * @Rest\Post("/api/login", name="api_login")
     *
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @SWG\Schema(type="string"),
     *     )
     * @SWG\Tag(name="Login")
     *
     */
    public function index(Request $request, JWTEncoderInterface $jwtEncoder,UserPasswordEncoderInterface $encoder)
    {

        $username = $request->get('username');
        $password = $request->get('password');


        $user = $this->getDoctrine()
            ->getRepository('App\Entity\User')
            ->findOneBy(['username' => $username]);
        if (!$user) {
            throw $this->createNotFoundException();
        }

        $isValid = $encoder->isPasswordValid($user, $password);

        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token =  $jwtEncoder->encode([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'exp' => time() + 3600 // 1 hour expiration
        ]);

        return new JsonResponse(['token' => $token]);


    }
}
