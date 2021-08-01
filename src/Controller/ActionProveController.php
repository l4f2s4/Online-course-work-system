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
use App\Entity\User;
use App\Entity\Student;
use App\Entity\Department;
use App\Entity\Result;


class ActionProveController extends AbstractController
{
            /**
             * @Route("/action/prove", name="action_prove")
             */
        public function departmentAction(Request $request): Response
        {
           $em=$this->getDoctrine()->getManager();
            $department =new Department();
            $dept=$request->request->get('dept');
           $takedept =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$dept]);
            if($request->isMethod('post'))
            {
              if(!empty($dept))
               {
               if(!$takedept)
                 {
                    $department->setName($dept);
                    $em->persist($department);
                    $em->flush();
                    return $this->redirectToRoute('add_department');
                 }
                 $this->addFlash('department',''.$dept.' department already exists');
                 return $this->render('main_engine/add_department.html.twig');
                }
               else
                {
                 $this->addFlash('department','Please enter department name');
                 return $this->render('main_engine/add_department.html.twig');
                }

            }
         }
               /**
             * @Route("/action/prove/course", name="course_prove")
             */
        public function CourseAction(Request $request): Response
        {
           $em=$this->getDoctrine()->getManager();
            $course =new Course();
            $course1=$request->request->get('course');
            $dept=$request->request->get('dept');
            $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
            $takedept =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$dept]);
            $takecourse =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['name'=>$course1]);
            if($request->isMethod('post'))
            {
              if(!empty($course1) & !empty($dept))
               {
               if(!$takecourse){
                    if($takedept)
                      {
                        $course->setName($course1);
                        $course->setDeptclass($takedept);
                        $em->persist($course);
                        $em->flush();
                        return $this->redirectToRoute('add_class');
                      }
                      $this->addFlash('course',$dept.' department not found');
                      return $this->render('main_engine/add_course.html.twig',['finddept'=>$finddept]);
                      }
                  $this->addFlash('course',$course1.' already exists');
                  return $this->render('main_engine/add_course.html.twig',['finddept'=>$finddept]);
                }
               else
                {
                 $this->addFlash('course','All field are required');
                 return $this->render('main_engine/add_course.html.twig',['finddept'=>$finddept]);
                }

            }

        }

                   /**
             * @Route("/action/prove/subject/register", name="subject_prove")
             */
        public function SubjectAction(Request $request): Response
        {
            $em=$this->getDoctrine()->getManager();
            $subject =new Subject();
            $connection=$em->getConnection();
            $subject1=$request->request->get('subject');

            $statement13=$connection->prepare("SELECT name FROM subject where name=:sub");
            $statement13->bindParam(':sub',$subject1);
            $statement13->execute();
            $takesubject=$statement13->fetchOne();


            if($request->isMethod('post'))
            {
              if(!empty($subject1))
               {
               if(!$takesubject)
               {
                    $subject->setName($subject1);
                    $em->persist($subject);
                    $em->flush();
                    return $this->redirectToRoute('add_subject');
                }
                $this->addFlash('subject','Subject name already exists');
                 return $this->render('main_engine/add_subject.html.twig');
                }
               else
                {
                $this->addFlash('subject','All field are required');
                 return $this->render('main_engine/add_subject.html.twig');
                }

            }

        }


            /**
             * @Route("/page/subject/assigned/teacher", name="show_class")
             */
        public function SubjectTeacherAction(Request $request): Response
        {
           $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            $statement13=$connection->prepare("
            SELECT * FROM subject
            ");
            $statement13->execute();
            $findsubject=$statement13->fetchAll();
             $statement133=$connection->prepare("
              SELECT DISTINCT subject.name,syear,user.username FROM `subject` inner join subject_year on
              subject.id=subject_year.subjectname_id inner join
              user on subject_year.steacher_id=user.id where title='teacher'
            ");
            $statement133->execute();
            $findprove=$statement133->fetchAll();

            $findteacher =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'teacher']);
             return $this->render('student_registerd/subject_teacher.html.twig',['findteacher'=>$findteacher,'findprove'=>$findprove,
             'findsubject'=>$findsubject]);


        }
}
