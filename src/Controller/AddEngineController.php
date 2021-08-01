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
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Result;
use App\Entity\Student;
use App\Entity\User;

class AddEngineController extends AbstractController
{
    /**
     * @Route("/engine/admin/add", name="add_engine")
     */
    public function adminpass(): Response
    {
    $user=$this->getUser();
    if($user != null){
        return $this->render('addAction/adminregistered.html.twig', [
            'controller_name' => 'AddEngineController',
        ]);
        }else
         {
                    return $this->redirectToRoute('just');
          }
    }
         /**
     * @Route("/engine/admin/add/department/name", name="departmentAdd")
     */
    public function addDepartment(): Response
    {
      $user=$this->getUser();
      if($user != null){
        return $this->render('main_engine/add_department.html.twig', [
            'controller_name' => 'AddEngineController'
        ]);
        }else
         {
                    return $this->redirectToRoute('just');
          }
    }
          /**
     * @Route("/engine/admin/add/department/registered/subject", name="departmentSubject")
     */
    public function addSubject(): Response
    {
    $user=$this->getUser();
    if($user != null){
        return $this->render('main_engine/add_subject.html.twig', [
            'controller_name' => 'AddEngineController'
        ]);
     }else
         {
                    return $this->redirectToRoute('just');
          }
    }
          /**
     * @Route("/engine/admin/add/course/name/register", name="courseAdd")
     */
    public function addCourse(): Response
    {
       $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
        return $this->render('main_engine/add_course.html.twig', [
            'controller_name' => 'AddEngineController','finddept'=>$finddept
        ]);
    }
        /**
     * @Route("/engine/admin/add/teachers", name="register_teacher")
     */
    public function addTeacher(): Response
    {
       $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
        return $this->render('addAction/addteacher.html.twig', [
            'controller_name' => 'AddEngineController','finddept'=>$finddept
        ]);
    }
       /**
     * @Route("/engine/student", name="register_student")
     */
    public function addStudent(): Response
    {
        $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
        $findclass =$this->getDoctrine()->getRepository(Course::class)->findAll();
        return $this->render('addAction/addstudent.html.twig', [
            'controller_name' => 'AddEngineController','finddept'=>$finddept,'findclass'=>$findclass
        ]);
    }
             /**
             * @Route("/engine/user/dashboard/admin", name="dataadmin")
             */
        public function dashboard(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
            {
               $user1=$this->getUser();
               $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $admin = new User();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $gender=$request->request->get('gender');
                $nation=$request->request->get('national');
                $title=$request->request->get('title');
                $marital=$request->request->get('marital');
                $connection=$em->getConnection();
                $statement2=$connection->prepare("select email from user where email=:just");
                $statement2->bindParam(':just', $email);
                $statement2->execute();
                $verifyemail=$statement2->fetchAll();

                     if($request->isMethod('post') & !empty($fname) & !empty($lname) & !empty($nation) & !empty($title) & !empty($marital) & !empty($email) & !empty($gender))
                     {
                             if(!$verifyemail)
                              {
                                $admin->setUsername($fname.' '.$lname);
                                $admin->setEmail($email);
                                $admin->setGender($gender);
                                $admin->setTitle($title);
                                $admin->setPassword($passwordEncoder->encodePassword(
                                                                $admin,
                                                                $lname
                                                            ));
                                $admin->setNationality($nation);
                                $admin->setMartalstatus($marital);
                                $admin->setRoles([
                                                "ROLE_ADMIN"
                                            ]);

                                $em->persist($admin);
                                $em->flush();

                                return $this->redirectToRoute('add_admin');
                                 }

                              else
                              {

                                     $this->addFlash('email',  'User email already exists');
                                     return $this->render('addAction/adminregistered.html.twig');
                              }
                     }
                     else
                     {
                                $this->addFlash('success',  'All fields are mandatory,please fill out');
                                return $this->render('addAction/adminregistered.html.twig');
                     }
            }

        /**
             * @Route("/engine/user/dashboard/teacher", name="teacherdash")
             */
        public function teacher(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
            {

                $em=$this->getDoctrine()->getManager();
                $teacher = new User();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $gender=$request->request->get('gender');
                $nation=$request->request->get('national');
                $dept=$request->request->get('dept');
                $marital=$request->request->get('marital');
                $connection=$em->getConnection();
                $statement2=$connection->prepare("select email from user where email=:just");
                $statement2->bindParam(':just', $email);
                $statement2->execute();
                $verifyemail=$statement2->fetchAll();
               $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
               $dept1 =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$dept]);
               if($request->isMethod('post'))
                {
                     if(!empty($fname) & !empty($lname) & !empty($nation) & !empty($dept) & !empty($marital) & !empty($email) & !empty($gender))
                     {
                             if(!$verifyemail)
                              {
                                if($dept1)
                                    {
                                        $teacher->setUsername($fname.' '.$lname);
                                        $teacher->setEmail($email);
                                        $teacher->setGender($gender);
                                        $teacher->setTitle("teacher");
                                        $teacher->setPassword($passwordEncoder->encodePassword(
                                                                        $teacher,
                                                                        $lname
                                                                    ));
                                        $teacher->setNationality($nation);
                                        $teacher->setMartalstatus($marital);
                                        $teacher->setRoles([
                                                        'ROLE_TEACHER'
                                                    ]);
                                        $teacher->setAddDepartment($dept1);
                                        $em->persist($teacher);
                                        $em->flush();
                                        return $this->redirectToRoute('add_teacher');

                                    }
                                     else
                                     {
                                                return $this->render('addAction/addteacher.html.twig',['finddept'=>$finddept]);
                                     }

                                 }

                              else
                              {

                                     $this->addFlash('email',  'User email already exists');
                                     return $this->render('addAction/addteacher.html.twig',['finddept'=>$finddept]);
                              }
                     }
                     else
                     {
                                $this->addFlash('success',  'All fields are mandatory,please fill out');
                                return $this->render('addAction/addteacher.html.twig',['finddept'=>$finddept]);
                     }
               }
            }
         /**
             * @Route("/engine/user/dashboard/add/student", name="dashstudent")
             */
        public function student(Request $request): Response
            {

                $em=$this->getDoctrine()->getManager();
                $student = new Student();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $regno=$request->request->get('reg');
                $gender=$request->request->get('gender');
                $nation=$request->request->get('national');
                $dept=$request->request->get('dept');
                $phoneno=$request->request->get('phoneno');
                $yos=$request->request->get('yos');
                $course=$request->request->get('course');
                $verifyemail=$this->getDoctrine()->getRepository(Student::class)->findOneBy(['email'=>$email]);
                $verifyregno=$this->getDoctrine()->getRepository(Student::class)->findOneBy(['regno'=>$regno]);
                $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
                $findclass =$this->getDoctrine()->getRepository(Course::class)->findAll();
                $dept1 =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$dept]);
                $course1 =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['name'=>$course]);
               if($request->isMethod('post'))
                {
                     if(!empty($fname) & !empty($phoneno) & !empty($yos) & !empty($regno) & !empty($lname) & !empty($nation) & !empty($dept) & !empty($course) & !empty($email) & !empty($gender))
                     {
                             if(!$verifyemail)
                              {
                               if(!$verifyregno)
                               {
                               if( (strlen($regno)>13)  ){

                                  if($course1)
                                  {
                                    if($dept1)
                                        {
                                            $student->setName($fname.' '.$lname);
                                            $student->setEmail($email);
                                            $student->setGender($gender);
                                            $student->setRegno($regno);
                                            $student->setPhoneno($phoneno);
                                            $student->setYoS($yos);
                                            $student->setNationality($nation);
                                            $student->setAddStudentClass($course1);
                                            $student->setSdept($dept1);
                                            $em->persist($student);
                                            $em->flush();
                                            return $this->redirectToRoute('student');

                                        }
                                         else
                                         {
                                                return $this->render('addAction/addstudent.html.twig',['finddept'=>$finddept,'findclass'=>$findclass]);
                                         }
                                     }
                                     else
                                     {
                                      return $this->render('addAction/addstudent.html.twig',['finddept'=>$finddept,'findclass'=>$findclass]);
                                     }
                                    }
                                   else
                                      {
                                                 $this->addFlash('regno',  "registration number is too short or you didn't start with nit/");
                                                return $this->render('addAction/addstudent.html.twig',['finddept'=>$finddept,'findclass'=>$findclass]);
                                      }
                                  }
                                    else
                                      {
                                                 $this->addFlash('regno',  'registration number already exists');
                                                return $this->render('addAction/addstudent.html.twig',['finddept'=>$finddept,'findclass'=>$findclass]);
                                      }

                                 }

                              else
                              {

                                     $this->addFlash('email',  'This email '.$email.' already exists');
                                     return $this->render('addAction/addstudent.html.twig',['finddept'=>$finddept,'findclass'=>$findclass]);
                              }
                     }
                     else
                     {
                                $this->addFlash('success',  'All fields are mandatory,please fill out');
                                return $this->render('addAction/addstudent.html.twig',['finddept'=>$finddept,'findclass'=>$findclass]);
                     }
               }
                return $this->render('addAction/addstudent.html.twig',['finddept'=>$finddept,'findclass'=>$findclass]);
            }

}
