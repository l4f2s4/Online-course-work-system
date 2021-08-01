<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Department;
use App\Entity\Course;
use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\Result;
use App\Entity\User;
class StudentController extends AbstractController
{
    /**
     * @Route("/student/registered/per/year", name="student")
     */
    public function index(): Response
    {
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();

        //symfony sql statement to print student details
        $decl=" SELECT count(*)
        FROM `student` where yo_s=1 order by yo_s";

        $statement12=$connection->prepare($decl);
        $statement12->execute();
        $student1=$statement12->fetchOne();
         $decl1=" SELECT count(*)
        FROM `student` where yo_s=2 order by yo_s";

        $statement121=$connection->prepare($decl1);
        $statement121->execute();
        $student2=$statement121->fetchOne();
         $decl2=" SELECT count(*)
        FROM `student` where yo_s=3 order by yo_s";

        $statement122=$connection->prepare($decl2);
        $statement122->execute();
        $student3=$statement122->fetchOne();
         $decl4=" SELECT count(*)
        FROM `student` where yo_s=4 order by yo_s";

        $statement123=$connection->prepare($decl4);
        $statement123->execute();
        $student4=$statement123->fetchOne();
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController','findyear1'=>$student1,'findyear2'=>$student2,'findyear3'=>$student3
            ,'findyear4'=>$student4
        ]);
    }

          /**
        *
     * @Route("/update/request/student/year/action/{y}", name="updaterequest")
     */
    public function updaterequest($y): Response
    {
             $user1=$this->getUser();
             $em=$this->getDoctrine()->getManager();
             $up =$this->getDoctrine()->getRepository(Student::class)->findBy(['YoS' => $y]);
             foreach($up as $i){
             $year=$i->getYoS();
             if($year<=3){
             $i->setYoS($year+1);
             }
             else{
              $i->setYoS($year);
             }

             }

             $em->flush();
            return $this->redirectToRoute('student');
    }


          /**
        *
     * @Route("/student/problem/reported/{token}", name="studentrequest")
     */
    public function studentrequest($token): Response
    {
     $entityManager = $this->getDoctrine()->getManager();
     $user = $entityManager->getRepository(Result::class)->findOneBy(['ResetToken' => $token]);
     $student=$user->getStundentNameR();
     $ID = $entityManager->getRepository(Student::class)->findOneBy(['id' => $student]);
     $regno=$ID->getRegno();
      return $this->render('external/problem_reported.html.twig', [
            'controller_name' => 'StudentController','token'=>$token,'regno'=>$regno
        ]);
    }
}
