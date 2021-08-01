<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Subject;
use App\Entity\SubjectYear;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Result;
use App\Entity\User;
use App\Entity\Student;

class PublishController extends AbstractController
{
    /**
     * @Route("/publish/{cm}/{id}/{y}", name="publish")
     */
    public function publish(
        Request $request,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator,$cm,$id,$y
    ): Response
    {

            $entityManager = $this->getDoctrine()->getManager();
            $subject= $entityManager->getRepository(SubjectYear::class)->findOneBy(['id'=>$id]);
            $sub=$subject->getId();
             $connection= $entityManager->getConnection();
            //symfony sql statement to print subject
            $decl12="SELECT result.id
                FROM
                 student inner join result on student.id=result.stundent_name_r_id inner join result_subject_year
                 on result.id=result_subject_year.result_id where result_subject_year.subject_year_id=:sub";
            $statement97=$connection->prepare($decl12);
            $statement97->bindParam(':sub',$sub);
            $statement97->execute();
            $tap=$statement97->fetchAll();

              try{
                  foreach($tap as $i=>$data)
                  {
                   $token = $tokenGenerator->generateToken();

                   $test = $entityManager->getRepository(Result::class)->findOneBy(['id'=>$data]);


                   if($test)
                     {
                           $test->setResetToken($token);
                           $test->setAddstatus("published");
                           $entityManager->flush();

                          $data=$test->getId();
                          $decl121="SELECT student.id
                            FROM
                            student
                            inner join result
                            ON
                             student.id=result.stundent_name_r_id
                            inner join result_subject_year
                            ON
                            result.id=result_subject_year.result_id
                            inner join subject_year
                            ON
                            result_subject_year.subject_year_id=subject_year.id
                            where result.id=:id";
                            $statement971=$connection->prepare($decl121);
                            $statement971->bindParam(':id',$data);
                            //$statement971->bindParam(':subid',$sub);
                            $statement971->execute();
                            $just=$statement971->fetchOne();


                           $decl1211="SELECT email
                            FROM
                                 student where id=:id";
                            $statement9711=$connection->prepare($decl1211);
                            $statement9711->bindParam(':id',$just);
                            $statement9711->execute();
                            $email=$statement9711->fetchOne();

                           $decl123="SELECT id
                            FROM
                                 student where id=:id";
                            $statement23=$connection->prepare($decl123);
                            $statement23->bindParam(':id',$just);
                            $statement23->execute();
                            $regno=$statement23->fetchOne();

                          $url = $this->generateUrl('result_posted', array('regno'=>$regno,'cm'=>$cm,'y'=>$y,'id'=>$sub,'token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
                            $message = (new \Swift_Message('CA for '.$cm.$y.' published you must sign it'))
                                ->setFrom('nitcollege920@gmail.com')
                                ->setTo($email)
                                ->setBody(
                                    "Please Click On This link : " . $url." to view and sign your ca.",
                                    'text/html'
                                );
                           $mailer->send($message);
                     }
               $this->addFlash('network','CA published successfully, and link was sent via student email .....');
                    }

            } catch (\Swift_TransportException $e) {
             $this->addFlash('network',$e->getMessage());
            }

           return $this->redirectToRoute('add_result_class',['id'=>$id,'y'=>$y]);
             }
 /**
     * @Route("/result_posted/{regno}/{cm}/{y}/{id}/{token}", name="result_posted")
     */
    public function resultposted(Request $request,string $cm,$y,int $regno,int $id, string $token)
    {
           $entityManager = $this->getDoctrine()->getManager();
           $connection= $entityManager->getConnection();
            //symfony sql statement to print subject
            $decl12="SELECT
                          student.regno,student.name,result.test1,result.test2,
                           result.individual1,result.individual2,result.group_work,
                           result.total,result.percent
                            FROM
                            student
                            inner join result
                            ON
                             student.id=result.stundent_name_r_id
                            inner join result_subject_year
                            ON
                            result.id=result_subject_year.result_id
                            inner join subject_year
                            ON
                            result_subject_year.subject_year_id=subject_year.id
                            where student.id=:regno and subject_year.id=:subid ";
            $statement97=$connection->prepare($decl12);
            $statement97->bindParam(':regno',$regno);
            $statement97->bindParam(':subid',$id);
            $statement97->execute();
            $pt=$statement97->fetchAll();
           return $this->render('class_subject/student_ca_sign.html.twig',['pt'=>$pt,'cm'=>$cm,'year'=>$y,'token'=>$token]);

    }

    /**
     * @Route("/result/{token}", name="sign_posted")
     */
    public function sign(string $token)
    {
         $entityManager = $this->getDoctrine()->getManager();
         $user = $entityManager->getRepository(Result::class)->findOneBy(['ResetToken' => $token]);
         /* @var $user User */
         if ($user === null) {
             return $this->render('external/token_error.html.twig');
             }
          $user->setResetToken(null);
          $user->setSignstatus('signed');
          $entityManager->flush();
          return $this->render('external/agree_sign_ca.html.twig');
    }
       /**
     * @Route("/result/disc/{token}", name="problem_posted")
     */
    public function problem(Request $request,string $token)
    {
         $entityManager = $this->getDoctrine()->getManager();
         $user = $entityManager->getRepository(Result::class)->findOneBy(['ResetToken' => $token]);
         $problem=$request->request->get('problem');
         /* @var $user User */
         if ($user === null) {
              return $this->render('external/token_error.html.twig');
             }
          $user->setResetToken(null);
          $user->setSignstatus('problem');
          $user->setReported($problem);
          $entityManager->flush();
          return $this->render('external/disagree_sign_ca.html.twig');
    }

  }




