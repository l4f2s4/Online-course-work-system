<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Entity\Department;
use App\Entity\Subject;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{main}", name="main_engine", defaults={"main":null})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if($this->isGranted('ROLE_ADMIN')){
             return $this->RedirectToRoute('admin_engine');
         }
         if($this->isGranted('ROLE_TEACHER')){
             return $this->RedirectToRoute('dashboard_engine');
         }

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main_engine/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/app/around/logout/", name="app_logout")
     */
    public function logout()
    {
      $this->get('security.token_storage')->setToken(null);
      $this->get('request')->getSession()->invalidate();

    }


     /**department function used to add,display,update,delete*/
     /**
     * @Route("/user/dashboard/available/department", name="add_department")
     */
    public function department()
    {
     $user1=$this->getUser();
           if($user1 != null)
          {
     $user2=$this->getUser()->getEmail();
     //$userid=$user2->getId();
     $em=$this->getDoctrine()->getManager();
     $connection=$em->getConnection();

     $department=$this->getDoctrine()->getRepository(Department::class)->findAll();


     return $this->render('main_engine/department.html.twig',['department'=>$department]);
     }else
         {
                    return $this->redirectToRoute('just');
          }

    }

       /**CR function used to add,display,update,delete classes*/
     /**
     * @Route("/user/dashboard/available/class/department", name="add_class")
     */
    public function CR()
    {
     $user1=$this->getUser();
           if($user1 != null)
          {
     $em=$this->getDoctrine()->getManager();
     $connection=$em->getConnection();

      //symfony sql statement to print class  details along with department
        $decl=" SELECT course.id,course.name FROM  `course` ";

        $statement93=$connection->prepare($decl);
        $statement93->execute();
        $cr=$statement93->fetchAll();

     return $this->render('main_engine/course.html.twig',['CR'=>$cr]);
     }else
         {
                    return $this->redirectToRoute('just');
          }

    }

     /**Subject function used to add,display,update,delete subject under department*/
     /**
     * @Route("/user/dashboard/available/subject/class/department", name="add_subject")
     */
    public function subject()
    {
     $user1=$this->getUser();
           if($user1 != null)
          {
     $em=$this->getDoctrine()->getManager();
     $connection=$em->getConnection();
     $findsubject=$this->getDoctrine()->getRepository(Subject::class)->findAll();

     //symfony sql statement to print subject details at most
    $decl12=" SELECT name FROM subject limit 2";
    $statement931=$connection->prepare($decl12);
    $statement931->execute();
    $sub=$statement931->fetchAll();


    //symfony sql statement to print class  details along with department
    $decl=" SELECT course.id as Un,course.name,department.name as dept FROM department inner join `course` on department.id=course.deptclass_id";
    $statement93=$connection->prepare($decl);
    $statement93->execute();
    $cr=$statement93->fetchAll();

    return $this->render('main_engine/subject.html.twig',['CT'=>$cr,'findsubject'=>$findsubject,'sub'=>$sub]);
 }else
         {
                    return $this->redirectToRoute('just');
          }
    }

      /**Subject class function used to add,display,update,delete subject under class*/
     /**
     * @Route("/user/dashboard/available/class/{id}/subject/All", name="add_subject_class")
     */
    public function subjectClass($id)
    {
     $user1=$this->getUser();
           if($user1 != null)
          {
     $em=$this->getDoctrine()->getManager();
     $connection=$em->getConnection();
    //symfony sql statement to print subject
    $decl12="SELECT course.name
        FROM
         `course`
        where course.id=:idc;
    ";
    $statement97=$connection->prepare($decl12);
    $statement97->bindParam(':idc', $id);
    $statement97->execute();
    $ct=$statement97->fetchOne();

    //symfony sql statement to print subject
    $decl=" SELECT course.name,subject.id,subject.name as sub
        FROM `subject` inner join subject_year on subject.id=subject_year.subjectname_id
        inner join `subject_year_course`
        on subject_year.id=subject_year_course.subject_year_id
        inner join  `course`
        on subject_year_course.course_id=course.id where course.id=:idnam;
    ";
    $statement93=$connection->prepare($decl);
    $statement93->bindParam(':idnam', $id);
    $statement93->execute();
    $cl=$statement93->fetchAll();

    return $this->render('main_engine/subjectBasedClass.html.twig',['CL'=>$cl,'ct'=>$ct,'idn'=>$id]);
        }else
         {
                    return $this->redirectToRoute('just');
          }
    }
}
