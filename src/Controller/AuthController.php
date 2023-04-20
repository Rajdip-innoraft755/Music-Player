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
 * AuthController is to handle the all the servicees before the user
 * successfully logged in.
 *
 *   @author rajdip <rajdip.roy@innoraft.com>
 */
class AuthController extends AbstractController
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
   * It stores a object of Registration Class to validate the user input data
   * at the time of registration.
   *
   *   @var object
   */
  public $registration;

  /**
   * This constructor is to initialize of the AuthController, basically
   * its initialize the object of the class and interface required by this
   * Controller.
   *
   *   @param object $em
   *     It accepts EntityManagerInterface object as parameter.
   *
   *   @return void
   *     Constructor returns nothing.
   */
  public function __construct(EntityManagerInterface $em){
    $this->em = $em;
    $this->user = new User();
    $this->registration = new Registration($this->em);
    $this->userRepo = $this->em->getRepository(User::class);
  }

  /**
   * This function to display the login form and validate the data input by the
   * user at the time of log in.
   *
   *   @Route("/", name = "login")
   *     This route take user to the first page of the application where a login
   *     form appears.
   *
   *   @param object $rq
   *     It accepts Request object as parameter to handle the input data.
   *
   *   @return Response
   *     Returns the response to the home page if user entered the correct
   *     credentials otherwise to the login page with proper error message.
   */
  public function login(Request $rq): Response
  {
    $data = $rq->request->all();
    if ($data) {
      $this->user = $this->userRepo->findOneBy([
        "emailId" => $data["emailId"],
        "password" => md5($data["password"])
      ]);
      if ($this->user) {
        setcookie("emailId", $data["userId"]);
        setcookie("active", TRUE);
        return $this->redirect("/home");
      }
      return $this->render("login.html.twig", [
        "loginErr" => "* Invalid Credentials ."
      ]);
    }
    return $this->render("login.html.twig");
  }

  /**
   * This function to display the registration form and validate the data
   * input by the user at the time of registration and store those in database.
   *
   *   @Route("/register", name = "register")
   *     This route take user to the registration page where a registration
   *     form appears to collect user details.
   *
   *   @param object $rq
   *     It accepts Request object as parameter to handle the input data.
   *
   *   @return Response
   *     Returns the response to the login page if user entered all valid
   *     details otherwise to the register page again with proper error message.
   *
   */
  public function register(Request $rq): Response
  {
    $data = $rq->request->all();
    if($data) {
      $error = $this->registration->validate($data);
      if(empty($error)) {
        $this->user->setter($data);
        $this->em->persist($this->user);
        $this->em->flush();
        dd($this->user->getInterest()[0]);
        return $this->redirect("/");
      }
      return $this->render("register.html.twig", [
        "error" => $error,
      ]);
    }
    return $this->render("register.html.twig");
  }

  /**
   * @Route("/available_email", name = "availableEmail")
   */
  public function avaiableEmailId(Request $rq): JsonResponse
  {
    $message = $this->registration->availableEmailId($rq->request->get("emailId"));
    return new JsonResponse(json_encode([
      "isAvialableEmailId" => $message,
    ]));
  }
}
