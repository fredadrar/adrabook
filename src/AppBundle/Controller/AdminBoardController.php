<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("admin")
 */
class AdminBoardController extends Controller
{
	/**
	 * @Route("/", name="index-admin")
	 * @return Response
	 */
	public function listBooksAction()
	{
		return $this->render('@App/adminboard/index.html.twig');
	}
}
