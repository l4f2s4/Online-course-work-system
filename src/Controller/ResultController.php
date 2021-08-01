<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Subject;
use App\Entity\SubjectYear;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Result;
use App\Entity\User;
use App\Entity\Student;

class ResultController extends AbstractController
{
    /**
     * @Route("/result/action/{regno}/{subject}/{y}", name="result")
     */
    public function index(Request $request,$regno,$subject,$y): Response
    {
      $user1=$this->getUser();
           if($user1 != null)
          {
        $em=$this->getDoctrine()->getManager();
        $totalmark =40;
        $test1=$request->request->get('test1');
        $test2=$request->request->get('test2');
        $individual1=$request->request->get('individual1');
        $individual2=$request->request->get('individual2');
        $group_work=$request->request->get('group_work');

        /*total possibility*/



        $material =$this->getDoctrine()->getRepository(SubjectYear::class)->findOneBy(['id'=>$subject]);
        $result =$this->getDoctrine()->getRepository(Result::class)->findOneBy(['id'=>$regno]);
        //$IDR=$material->getId();
        //$result=new Result();
            if($request->isMethod('post'))
            {
                   if(empty($test1)){
                    $test11=0.0;
                    $result->setTest1($test11);
                    }
                    if(empty($test2)){
                    $test12=0.0;
                    $result->setTest2($test12);
                    }
                    if(empty($individual1)){
                    $individual11=0.0;
                    $result->setIndividual1($individual11);
                    }
                    if(empty($group_work)){
                    $group_work1=0.0;
                    $result->setGroupWork($group_work1);
                    }
                   if(empty($individual2)){
                    $individual12= 0.0;
                    $result->setIndividual2($individual12);
                    }
                    /*un empty field*/
                    if(!empty($test1)){
                    $test11=$test1;
                    $result->setTest1($test1);
                    }
                    if(!empty($test2)){
                    $test12=$test2;
                    $result->setTest2($test2);
                    }
                    if(!empty($individual1)){
                    $individual11=$individual1;
                    $result->setIndividual1($individual11);
                    }
                    if(!empty($group_work)){
                    $group_work1=$group_work;
                    $result->setGroupWork($group_work1);
                    }
                   if(!empty($individual2)){
                    $individual12=$individual2;
                    $result->setIndividual2($individual12);
                    }
                  $all=$test11+$test12+$individual11+$individual12+$group_work1;

                if($all<=40)
                 {
                    $percent=($all/$totalmark) * 100;
                    $result->setTotal($all);
                    $result->setPercent($percent);
                    $result->setSignstatus('unsigned');
                    $result->setAddstatus('added');
                   // $em->persist($result);
                    $em->flush();
                   return $this->redirect($this->generateUrl('add_result_class',['id'=>$subject,'y'=>$y]));

                  }
                 else
                  {
                    $this->addFlash('subjectmark','error, ....total mark above 40');
                     return $this->render('main_engine/class_subject.html.twig');
                  }

             }
              }else
         {
                    return $this->redirectToRoute('just');
          }
    }

}