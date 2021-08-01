<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Subject;
use App\Entity\SubjectYear;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Result;
use App\Entity\User;
use App\Entity\Student;

class StudentRegisterdController extends AbstractController
{
    /**
     * @Route("/student/registered/{regno}/{id}/{y}/{semester}", name="student_registered")
     */
    public function index($regno,$id,$y,$semester): Response
    {
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $takestudent =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id'=>$regno]);
        $takecourse =$this->getDoctrine()->getRepository(Course::class)->findAll();
        $name=$takestudent->getRegno();


        $dcl1="
        select DISTINCT subject.name,subject_year.id,syear from course
          inner join student on course.id=student.add_student_class_id inner join
         subject_year_course on student.add_student_class_id=subject_year_course.course_id
         inner join subject_year on subject_year_course.subject_year_id=subject_year.id
         inner join subject on
         subject_year.subjectname_id=subject.id
         WHERE course.id=:id and subject_year.semester=:semester and student.yo_s=:year and subject_year.id not in
         (  select subject_year.id from course inner join
         subject_year_course on course.id=subject_year_course.course_id
         inner join subject_year on subject_year_course.subject_year_id=subject_year.id
         inner join subject on
         subject_year.subjectname_id=subject.id inner join student_subject_year on subject_year.id=student_subject_year.subject_year_id inner join student on student_subject_year.student_id=student.id where
         subject_year.semester=:semester and course.id=:id and student.id=:regno and student.yo_s=:year)
        ";
        $statement13=$connection->prepare($dcl1);
        $statement13->bindParam(':id',$id);
        $statement13->bindParam(':regno',$regno);
        $statement13->bindParam(':year',$y);
        $statement13->bindParam(':semester',$semester);
        $statement13->execute();
        $classtest=$statement13->fetchAll();
        $dcl="select subject_year.id,subject.name,syear from course inner join
         subject_year_course on course.id=subject_year_course.course_id
         inner join subject_year on subject_year_course.subject_year_id=subject_year.id
         inner join subject on
         subject_year.subjectname_id=subject.id inner join student_subject_year on subject_year.id=student_subject_year.subject_year_id inner join student on student_subject_year.student_id=student.id where
         subject_year.semester=:semester and course.id=:id and student.id=:regno and student.yo_s=:year";
        $statement131=$connection->prepare($dcl);
        $statement131->bindParam(':id',$id);
        $statement131->bindParam(':regno',$regno);
        $statement131->bindParam(':year',$y);
        $statement131->bindParam(':semester',$semester);
        $statement131->execute();
        $registered=$statement131->fetchAll();


        return $this->render('student_registerd/index.html.twig', [
            'controller_name' => 'StudentRegisterdController','name'=>$name,'registered'=>$registered,'takecourse'=>$takecourse,'classtest'=>$classtest,'regno'=>$regno,'nt'=>$id,'year'=>$y,'semester'=>$semester
        ]);
    }


               /**
             * @Route("/student/subject/register/action/{regno}/{nt}/{id}/{y}/{semester}", name="subject_registered")
             */
        public function SubjectStudent(Request $request,$id,$regno,$nt,$y,$semester): Response
        {

           $em=$this->getDoctrine()->getManager();
            $result =new Result();
            $takestudent =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id'=>$regno]);
            $connection=$em->getConnection();
            $takesubject =$this->getDoctrine()->getRepository(SubjectYear::class)->findOneBy(['id'=>$id]);
             if($request->isMethod('POST')){
               if($takestudent)
               {
                    $result->addSubjectResult($takesubject);
                    $result->setStundentNameR($takestudent);
                    $result->setAddRegister("register");
                    $result->setAddstatus('notadded');
                    $takesubject->addAddStudentSubject($takestudent);
                    $em->persist($result);
                    $em->flush();

                    return $this->redirectToRoute('student_registered',['regno'=>$regno,'id'=>$nt,'y'=>$y,'semester'=>$semester]);
                }
              }
         }




}
