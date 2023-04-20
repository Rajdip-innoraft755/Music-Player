<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Registration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * HomeController handles the services after the user successfully logged in,
 * It manages upload music, play music, add music to favourite, update user
 * details etc.
 *
 *   @author rajdip <rajdip.roy@innoraft.com>
 */
class HomeController extends AbstractController
{
  /**
   * It stores a object of EntityManagerInterface class
   * It is to manage persistance and retriveal Entity object from Database.
   *
   *   @var object
   */
  public $em;

  /**
   * It stores a object of UserRepository class, it is to fetch data
   * from user table of database.
   *
   *   @var object
   */
  public $userRepo;

  /**
   * It stores a object of User Class to set and get data from database
   * through doctrine.
   *
   *   @var object
   */
  public $user;

  /**
   * This constructor is to initialize of the HomeController, basically
   * its initialize the object of the class and interface required by this
   * Controller.
   *
   *   @param object $em
   *     It accepts EntityManagerInterface object as parameter.
   *
   *   @return void
   *     Constructor returns nothing.
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->user = new User();
    $this->userRepo = $this->em->getRepository(User::class);
  }

  /**
   *   @Route("/home","home")
   */
  public function home(): Response
  {
    return $this->render("home.html.twig");
  }
}
