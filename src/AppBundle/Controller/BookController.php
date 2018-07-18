<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\Loan;
use AppBundle\Entity\User;
use AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("book")
 */
class BookController extends Controller
{
	/**
	 * Lister les livres.
	 *
	 * @Route("/", name="book_index")
	 * @Method("GET")
	 * @IsGranted("ROLE_USER")
	 */
	public function indexAction()
	{
		$books = $this->getDoctrine()->getRepository(Book::class)->findAll();
		
		return $this->render('@App/book/index.html.twig', [
			'books'=>$books,
		]);
	}
	
	/**
	 * Afficher le détail d'un livre.
	 *
	 * @Route("/show/{id}", name="book_show")
	 * @Method("GET")
	 * @IsGranted("ROLE_USER")
	 *
	 * @param Book $book
	 *
	 * @return Response
	 */
	public function showAction(Book $book)
	{
		return $this->render('@App/book/show.html.twig', ['book'=>$book]);
	}
	
	/**
	 * Créer/Modifier un livre.
	 *
	 * @Route("/form/{id}", name="book_form", defaults={"id" = null})
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Request $request
	 * @param null    $id
	 *
	 * @return RedirectResponse|Response
	 */
	public function formAction(Request $request, $id = null)
	{
		if ($id === null) {
			$book = new Book();
		}
		else {
			$book = $this->getDoctrine()->getRepository(Book::class)->find($id);
			if ($book === null){
				return $this->redirectToRoute('book_index');
			}
		}
		
		$form = $this->createForm(BookType::class, $book);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){

			$em = $this->getDoctrine()->getManager();
			$em->persist($book);
			$em->flush();
			return $this->redirectToRoute('book_index');
		}

		return $this->render('@App/book/form.html.twig', [
			'book' => $book,
			'form' => $form->createView()
		]);
	}
	
	/**
	 * Supprimer un livre.
	 *
	 * @Route("/delete/{id}", name="book_delete")
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Request $request
	 * @param Book    $book
	 *
	 * @return RedirectResponse|Response
	 */
	public function deleteAction(Request $request, Book $book)
	{
		$form = $this->createFormBuilder()
					 ->setAction($this->generateUrl('book_delete', ['id' => $book->getId(),]))
					 ->getForm()
		;
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){
			
			if (!$book->getLoans()[0]) {
				$em = $this->getDoctrine()->getManager();
				$em->remove($book);
				$em->flush();
				
				$this->addFlash('success','Vous avez supprimé '. $book->getTitle() .'.');
			}
			else {
				$this->addFlash('error','Vous ne pouvez pas supprimer un livre emprunté !');
			}
			return $this->redirectToRoute('book_index');
		}
		
		return $this->render('@App/book/delete.html.twig', [
			'book' => $book,
			'form' => $form->createView()
		]);
	}
	
	/**
	 * Emprunter un livre.
	 *
	 * @Route("/borrow/{id}", name="book_borrow")
	 * @IsGranted("ROLE_USER")
	 *
	 * @param Request  $request
	 * @param Book     $book
	 * @param Security $security
	 *
	 * @return RedirectResponse|Response
	 * @throws \Exception
	 */
	public function borrowAction(Request $request, Book $book, Security $security)
	{
		$form = $this->createFormBuilder()
					 ->setAction($this->generateUrl('book_borrow', ['id' => $book->getId(),]))
					 ->getForm()
		;
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){
			
			/** @var User $user */
			$user = $security->getUser();
			
			$loans = $book->getLoans();
			
			if ($loans[0]) {
				$this->addFlash('error','Le livre '. $book->getTitle() .' n\'est pas disponible !');
				return $this->redirectToRoute('book_index');
			}
			if (!$user->getActive()) {
				$this->addFlash('error','Vous ne pouvez pas emprunter de livre actuellement !');
				return $this->redirectToRoute('book_index');
			}
			
			$loan = new Loan();
			$loan->setLoanDate(new \DateTime)
				 ->setUser($user)
				 ->setBook($book);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($loan);
			$em->flush();
			
			$this->addFlash('success','Vous avez emprunté '. $book->getTitle() .'. Veuillez le rendre avant le '. $loan->getLoanDate()->add(new \DateInterval('P15D'))->format('d-m-Y').'.');
			return $this->redirectToRoute('book_index');
		}
		
		return $this->render('@App/book/borrow.html.twig', [
			'book' => $book,
			'form' => $form->createView()
		]);
	}
	
	/**
	 * Rendre un livre.
	 *
	 * @Route("/return/{id}", name="book_return")
	 * @IsGranted("ROLE_USER")
	 *
	 * @param Request  $request
	 * @param Book     $book
	 * @param Security $security
	 *
	 * @return RedirectResponse|Response
	 */
	public function returnAction(Request $request, Book $book, Security $security)
	{
		$loans = $book->getLoans();
		
		if ($loans[0] && $loans[0]->getUser() === $security->getUser()) {
			
			$em = $this->getDoctrine()->getManager();
			$em->remove($loans[0]);
			$em->flush();
			
			$this->addFlash('success','Vous avez rendu '. $book->getTitle() .' ! Merci.');
			return $this->redirectToRoute('book_index');
		}
	}
	
}
