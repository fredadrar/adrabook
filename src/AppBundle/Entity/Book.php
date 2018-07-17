<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class Book
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text", length=2000)
     */
    private $abstract;
	
	/**
	 * @var Loan[]
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Loan", mappedBy="book")
	 */
	private $loans;
	
	
	public function __construct()
	{
		$this->loans = new ArrayCollection();
	}


//******************************************************************
//GETTERS-SETTERS
//******************************************************************
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     *
     * @return Book
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }
    
    /**
     * Add loan
     *
     * @param Loan $loan
     *
     * @return Book
     */
    public function addLoan(Loan $loan)
    {
        $this->loans[] = $loan;

        return $this;
    }

    /**
     * Remove loan
     *
     * @param Loan $loan
     */
    public function removeLoan(Loan $loan)
    {
        $this->loans->removeElement($loan);
    }

    /**
     * Get loans
     *
     * @return Loan[]
     */
    public function getLoans()
    {
        return $this->loans;
    }
}
