<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("admin/user")
 */
class UserController extends Controller
{
	/**
	 * Lister les users.
	 *
	 * @Route("/", name="user_index")
	 * @Method("GET")
	 * @IsGranted("ROLE_ADMIN")
	 */
	public function indexAction()
	{
		$users = $this->getDoctrine()->getRepository(User::class)->findAll();
		
		return $this->render('@App/admin/user/index.html.twig', ['users'=>$users]);
	}
	
	/**
	 * Créer/Modifier un user.
	 *
	 * @Route("/form/{id}", name="user_form", defaults={"id" = null})
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Request   $request
	 * @param User|null $user
	 *
	 * @return RedirectResponse|Response
	 */
	public function formAction(Request $request, User $user = null)
	{
		$em = $this->get('fos_user.user_manager');
		if ($user === null) {
			$user = $em->createUser();
		}
		
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){
			
			$user->setEnabled(true);
			$em->updateUser($user);
			
			return $this->redirectToRoute('user_index');
		}
		
		return $this->render('@App/admin/user/form.html.twig', [
			'user' => $user,
			'form' => $form->createView()]);
	}
	
	/**
	 * Supprimer un user.
	 *
	 * @Route("/delete/{id}", name="user_delete")
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Request $request
	 * @param User    $user
	 *
	 * @return RedirectResponse|Response
	 */
	public function deleteAction(Request $request, User $user)
	{
		$form = $this->createFormBuilder()
					 ->setAction($this->generateUrl('user_delete', ['id' => $user->getId(),]))
					 ->getForm()
		;
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){
			
			$em = $this->get('fos_user.user_manager');
			$em->deleteUser($user);
			
			return $this->redirectToRoute('user_index');
		}
		
		return $this->render('@App/admin/user/delete.html.twig', [
			'user' => $user,
			'form' => $form->createView()
		]);
	}
}
