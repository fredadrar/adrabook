<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Loan;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("loan")
 */
class LoanController extends Controller
{
	/**
	 * Lister les emprunts.
	 *
	 * @Route("/", name="loan_index")
	 * @Method("GET")
	 * @IsGranted("ROLE_ADMIN")
	 */
	public function indexAction()
	{
		$loans = $this->getDoctrine()->getRepository(Loan::class)->findAll();
		
		return $this->render('@App/loan/index.html.twig', [
			'loans'=>$loans,
		]);
	}
	
	/**
	 * Lister les emprunts d'un user spécifique.
	 *
	 * @Route("/list", name="my_loans")
	 * @Method("GET")
	 * @IsGranted("ROLE_USER")
	 *
	 * @param Security $security
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listMyLoansAction(Security $security)
	{
		$user = $security->getUser();
		$loans = $this->getDoctrine()->getRepository(Loan::class)->findBy(['user' => $user]);
		
		return $this->render('@App/loan/index.html.twig', [
			'loans'=>$loans,
		]);
	}
	
	/**
	 * Lister les emprunts en retard.
	 *
	 * @Route("/late", name="late_index")
	 * @Method("GET")
	 * @IsGranted("ROLE_ADMIN")
	 **
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listLateLoansAction()
	{
		$loans = $this->getDoctrine()->getRepository(Loan::class)->findAll();
		
		return $this->render('@App/loan/late.html.twig', [
			'loans'=>$loans,
		]);
	}
}