<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="loan")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LoanRepository")
 */
class Loan
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
     * @var \DateTime
	 *
     * @ORM\Column(name="loan_date", type="datetime")
     */
    private $loanDate;
	
	/**
	 * @var Book
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book", inversedBy="loans")
	 * @ORM\JoinColumn(nullable=false)
	 */
    private $book;
	
	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="loans")
	 * @ORM\JoinColumn(nullable=false)
	 */
    private $user;


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
     * Set loanDate
     *
     * @param \DateTime $loanDate
     *
     * @return Loan
     */
    public function setLoanDate($loanDate)
    {
        $this->loanDate = $loanDate;

        return $this;
    }

    /**
     * Get loanDate
     *
     * @return \DateTime
     */
    public function getLoanDate()
    {
        return $this->loanDate;
    }

    /**
     * Set book
     *
     * @param $book
     *
     * @return Loan
     */
    public function setBook($book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }
	
	/**
	 * Set user
	 *
	 * @param $user
	 *
	 * @return Loan
	 */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
