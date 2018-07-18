<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

//Redirige sur la bonne page en fonction du rôle :
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	protected $router;
	protected $authorizationChecker;
	
	public function __construct(Router $router, AuthorizationChecker $authorizationChecker)
	{
		$this->router = $router;
		$this->authorizationChecker = $authorizationChecker;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		$response = null;
		
		if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
			$response = new RedirectResponse($this->router->generate('book_index'));
		}
		else if ($this->authorizationChecker->isGranted('ROLE_USER')) {
			$response = new RedirectResponse($this->router->generate('book_index'));
		}
		
		return $response;
	}
}