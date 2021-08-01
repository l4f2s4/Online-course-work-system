<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Department;
use App\Entity\Subject;
use App\Entity\Course;
use App\Entity\Result;

class TeachController extends AbstractController
{
    /**
     * @Route("/teach/show/subject", name="teach")
     */
    public function index(): Response
    {

    $user1=$this->getUser();
    if($user1 != null){
     $em=$this->getDoctrine()->getManager();
    $connection=$em->getConnection();
    $uid=$user1->getId();
     //symfony sql statement to print subject details at most
    $decl120="SELECT DISTINCT subject.name,subject.id as ID,subject_year.syear,subject_year.semester FROM `subject` inner join subject_year on
              subject.id=subject_year.subjectname_id inner join
              user on subject_year.steacher_id=user.id where title='teacher' and user.id=:id limit 6";
    $statement933=$connection->prepare($decl120);
    $statement933->bindParam(':id',$uid);
    $statement933->execute();
    $findsubject=$statement933->fetchAll();


        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeachController','findsubject'=>$findsubject
        ]);
         }else
         {
                    return $this->redirectToRoute('just');
          }
    }

    /**
     * @Route("/teach/show/subject/class/{id}", name="class_show")
     */
    public function show($id): Response
    {
      $user1=$this->getUser();
    if($user1 != null){
     $em=$this->getDoctrine()->getManager();
     $findc =$this->getDoctrine()->getRepository(Subject::class)->findOneBy(['id'=>$id]);
     $cname=$findc->getName();
    $connection=$em->getConnection();

     //symfony sql statement to print subject details at most

    $decl120="SELECT course.name  FROM course
        inner join subject_year_course
         on course.id=subject_year_course.course_id inner join subject_year
         on subject_year_courser.subject_year_id=subject_year.id inner join subject on
         subject.id=subject_year.subjectname_id
        WHERE subject.id=:id";
    $statement933=$connection->prepare($decl120);
    $statement933->bindParam(':id',$id);
    $statement933->execute();
    $findsubject=$statement933->fetchAll();

        return $this->render('teacher/main.html.twig', [
            'controller_name' => 'TeachController','findsubject'=>$findsubject,'cname'=>$cname
        ]);
         }else
         {
                    return $this->redirectToRoute('just');
          }
    }

          /**Subject class function used to add,display,update,delete subject under class*/
     /**
     * @Route("/user/dashboard/available/class/semester/{id}/subject/All", name="class_semester_subject")
     */
    public function semester1($id)
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
    $decl=" SELECT course.name,subject.id,subject.name as sub,syear,user.username
        FROM `subject`
        inner join `subject_year`
        on subject.id=subject_year.subjectname_id
        inner join subject_year_course on subject_year.id=subject_year_course.subject_year_id
        inner join  `course`
        on subject_year_course.course_id=course.id  inner join user on subject_year.steacher_id=user.id where title='teacher' and course.id=:idnam and subject_year.semester='semester1'
    ";
    $statement93=$connection->prepare($decl);
    $statement93->bindParam(':idnam', $id);
    $statement93->execute();
    $cl=$statement93->fetchAll();

    return $this->render('class_subject/semesterone.html.twig',['CL'=>$cl,'ct'=>$ct,'idn'=>$id]);
        }else
         {
                    return $this->redirectToRoute('just');
          }
    }
              /**Subject class function used to add,display,update,delete subject under class*/
     /**
     * @Route("/user/dashboard/available/class/semester/two/{id}/subject/All", name="semester2_subject")
     */
    public function semester2($id)
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
    $decl="SELECT course.name,subject.id,subject.name as sub,syear,user.username
        FROM `subject`
        inner join `subject_year`
        on subject.id=subject_year.subjectname_id
        inner join subject_year_course on subject_year.id=subject_year_course.subject_year_id
        inner join  `course`
        on subject_year_course.course_id=course.id  inner join user on subject_year.steacher_id=user.id where title='teacher' and course.id=:idnam and subject_year.semester='semester2'
    ";
    $statement93=$connection->prepare($decl);
    $statement93->bindParam(':idnam', $id);
    $statement93->execute();
    $cl=$statement93->fetchAll();

    return $this->render('class_subject/semestertwo.html.twig',['CL'=>$cl,'ct'=>$ct,'idn'=>$id]);
        }else
         {
                    return $this->redirectToRoute('just');
          }
    }
}
