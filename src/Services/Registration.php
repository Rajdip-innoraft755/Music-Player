<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Registration class is for validate user data , imagestoring and
 * generate the uniqueId .
 *
 *   @author rajdip <rajdip.roy@innoraft.com>
 */
class Registration
{
  /**
   * This is a object of EntityManagerInterface class
   * It is to manage persistance and retriveal Entity object from Database.
   *
   *   @var object
   */
  public $em;

  /**
   * This is a object of EntityRepository class
   * It is to fetch data from user table of database.
   *
   *   @var object
   */
  public $userRepo;

  /**
   * This constructor initializes object of Register Class also provides
   * access to EntityManagerInterface object .
   *
   *   @param  object $em
   *     It is to manage persistance and retriveal Entity object from Database.
   *
   *   @return void
   *     Constructor returns nothing .
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->userRepo = $this->em->getRepository(User::class);
  }

  /**
   *  This method checks the whether the user gives the valid inputs or not
   *  and stores the errors in an array and returned it to the calling method.
   *
   *   @param  array $data
   *     This method accepts user data in form of an array as parameter.
   *
   *   @return array
   *     This method returns the errors in form an array
   */
  public function validate(array $data): array
  {
    $error = [];

    // Checks whether the first name contains only alphabet or not .
    // If first name contains other than alphabets then store the error .
    if (!(preg_match("/^[a-zA-Z ]*$/", $data["fName"]))) {
      $error["fName"] = "* first name only contains alphabet.";
    }

    // Checks whether the last name contains only alphabet or not .
    // If last name contains other than alphabets then store the error .
    if (!(preg_match("/^[a-zA-Z ]*$/", $data["lName"]))) {
      $error["lName"] = "* last name only contains alphabet.";
    }

    // Checks whether the email id is in valid format or not.
    // If email id is not in valid format then store the email format error
    // otherwise check whether the email id is already used or not, if used
    // then store that error also.
    if ((preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $data["emailId"]))) {
      $isAvailable = $this->availableEmailId($data["emailId"]);
      if(!empty($isAvailable)){
        $error["emailId"] = $isAvailable;
      }
    }
    else {
      $error["emailId"] = "* not a valid email.";
    }

    // Checks whether the password follows the following checkpoints or not more
    // than 8 characters atleast one uppercase and one lowercase and one digit
    // and one special characters(@, $, #, !, %, *, ?, &).
    // If the password does not match these conditions then store the error.
    if (!(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$#!%*?&])[A-Za-z\d@#$!%*?&]{8,}$/", $data["password"]))) {
      $error["password"] = "* weak password.";
    }
    return $error;
  }

  /**
   * This method is used to check whether the email id is already exits in
   * database or not.
   *
   *   @param string $emailId
   *     Accepts email id as input .
   *
   *   @return string
   *     Returns error message if the email id already taken otherwise empty string.
   */
  public function availableEmailId(string $emailId): string
  {
    if ($this->userRepo->findBy(["emailId" => $emailId])) {
      return "* Email Id already exists.";
    }
    return "";
  }
}
?>

