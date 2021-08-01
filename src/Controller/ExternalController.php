<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\SubjectYear;
use App\Entity\Department;
use App\Entity\Course;
use App\Entity\Result;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;

class ExternalController extends AbstractController
{
    /**
     * @Route("/external", name="external")
     */
    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('kind');
             if(!empty($email)){
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('verify', 'Email not found!');
                return $this->render('external/index.html.twig');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('verify','Connection problem');
                return $this->render('external/index.html.twig');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Password Reset Link For SRMS'))
                ->setFrom('nitcollege920@gmail.com')
                ->setTo($user->getEmail())
                
                ->setBody(
                    "Please Click On This link : " . $url." to reset your password.",
                    'text/html'
                );



              if (!$mailer->send($message))
            {
              echo "Failures:";
              print_r($failures);
            }
              $mailer->send($message);

            return $this->redirectToRoute('main_engine');
             }
             else{
                $this->addFlash('verify', 'This field cannot be empty');
                return $this->render('external/index.html.twig');
             }
        }

        return $this->render('external/index.html.twig');
     }

 /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

 if ($request->isMethod('POST'))
     {
           $newpass=$request->request->get('newpass');
           $conf=$request->request->get('confirm');
           if(!empty($newpass) || !empty($conf))
           {
                if(strlen($newpass)>7)
                {
                     if($newpass==$conf)
                       {
                            $entityManager = $this->getDoctrine()->getManager();

                            $user = $entityManager->getRepository(User::class)->findOneBy(['ResetToken' => $token]);
                            /* @var $user User */

                            if ($user === null) {
                                $this->addFlash('dangerToken', 'Token Incorrect');
                                return $this->render('external/reset_password.html.twig', ['token' => $token]);
                            }

                            $user->setResetToken(null);
                            $user->setPassword($passwordEncoder->encodePassword($user, $conf));
                            $entityManager->flush();


                            return $this->redirectToRoute('main_engine');
                     }
                    else
                    {
                            $this->addFlash('dangerToken',  'Password mismatch!');
                            return $this->render('external/reset_password.html.twig', ['token' => $token]);
                    }
                }
             else
                {
                    $this->addFlash('dangerToken', 'Password must be at least 8 characters!');
                    return $this->render('external/reset_password.html.twig', ['token' => $token]);
               }
          }
         else
         {
                $this->addFlash('dangerToken',  'All fields are required!');
                return $this->render('external/reset_password.html.twig', ['token' => $token]);
         }
    }
   else
    {

        return $this->render('external/reset_password.html.twig', ['token' => $token]);
    }

}

 /**
     * @Route("/student/course/register/area/semester", name="course_register")
     */
    public function courseregister(Request $request)
    {
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
               $findcourse=$this->getDoctrine()->getRepository(Course::class)->findAll();
               if($request->isMethod('POST'))
               {
                    $regno=strip_tags($request->request->get('search'));
                    $std=strip_tags($request->request->get('regno'));
                    $semester=strip_tags($request->request->get('semester'));


                    if(!empty($regno) & !empty($std) & !empty($semester))
                    {
                      $search =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['name'=>$regno]);
                      $part =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['regno'=>$std]);

                      $semester1 =$this->getDoctrine()->getRepository(SubjectYear::class)->findOneBy(['semester'=>$semester]);

                     if($part){
                      if($search){
                          if($semester1){
                             $yos=$part->getYoS();
                            $classid=$search->getId();
                            $cl=$part->getId();
                            $decl="select count(*) from student inner join course on
                            student.add_student_class_id=course.id
                            where student.id=:cl and course.id=:classid ";
                            $statement13=$connection->prepare($decl);
                            $statement13->bindParam(':cl',$cl);
                            $statement13->bindParam(':classid',$classid);
                            $statement13->execute();
                            $tied=$statement13->fetchOne();
                          if($tied != 0){
                           return $this->redirectToRoute('student_registered',['regno'=>$cl,'id'=>$classid,'y'=>$yos,'semester'=>$semester]);
                            }
                         else{
                            $this->addFlash('searchpage',"search for ".$regno. " and ".$std." didn't match our record");
                            return $this->render('student_registerd/course_register.html.twig',['findcourse'=>$findcourse]);
                            }
                            }
                            else{
                             return $this->render('student_registerd/course_register.html.twig',['findcourse'=>$findcourse]);
                            }
                          }
                        $this->addFlash('searchpage',"search for ".$regno. " not found");
                        return $this->render('student_registerd/course_register.html.twig',['findcourse'=>$findcourse]);
                        }
                        $this->addFlash('searchpage',"Registration no ".$std. " not found");
                        return $this->render('student_registerd/course_register.html.twig',['findcourse'=>$findcourse]);
                    }
                    return $this->render('student_registerd/course_register.html.twig',['findcourse'=>$findcourse]);
                }
        return $this->render('student_registerd/course_register.html.twig',['findcourse'=>$findcourse]);

    }

   /**
     * @Route("/teacher/dashboard/show", name="dashboard_engine" )
     */
    public function index(): Response
    {


        $user=$this->getUser();

           if($user != null)
          {
        $em=$this->getDoctrine()->getManager();
        $uid=$user->getId();
        $connection=$em->getConnection();

        //symfony sql statement for counting ca above the limit
        $statement11=$connection->prepare("SELECT department.name FROM `user` inner join
        department on
        user.add_department_id=department.id
        where user.id=:id limit 4 ");
        $statement11->bindParam(':id',$uid);
        $statement11->execute();
        $finddept=$statement11->fetchOne();

        $statement13=$connection->prepare("SELECT DISTINCT subject.name,subject.id as ID,subject_year.syear,subject_year.semester FROM `subject` inner join subject_year on
              subject.id=subject_year.subjectname_id inner join
              user on subject_year.steacher_id=user.id where title='teacher' and user.id=:id");
        $statement13->bindParam(':id',$uid);
        $statement13->execute();
        $findsubject=$statement13->fetchAll();




        return $this->render('teacher/teacherdashboard.html.twig', [
            'finddept'=>$finddept,'findsubject'=>$findsubject
        ]);
         }else
         {
                    return $this->redirectToRoute('just');
          }

    }

}
