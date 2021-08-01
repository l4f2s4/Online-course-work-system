<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User;
use App\Entity\SubjectYear;
use App\Entity\Department;
use App\Entity\Subject;
use App\Entity\Course;
use App\Entity\Result;

class ClassSubjectController extends AbstractController
{
    /**
     * @Route("/class/subject/{ct}", name="class_subject")
     */
    public function index($ct): Response
    {
    $em=$this->getDoctrine()->getManager();
   $connection=$em->getConnection();
        $statement133=$connection->prepare("
              SELECT subject.name FROM `subject` ");
        $statement133->execute();
        $findsubject=$statement133->fetchAll();
        $findteacher=$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'teacher']);

        return $this->render('class_subject/class_subject.html.twig', [
            'controller_name' => 'ClassSubjectController','ct'=>$ct,'findsubject'=>$findsubject,'findteacher'=>$findteacher
        ]);
    }
     /**
     * @Route("/class/subject/sem/{ct}", name="sem2_subject")
     */
    public function sem($ct): Response
    {
          $em=$this->getDoctrine()->getManager();
          $connection=$em->getConnection();
        $statement133=$connection->prepare("
            SELECT subject.name FROM `subject`
            ");
        $statement133->execute();
        $findsubject=$statement133->fetchAll();
        $findteacher=$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'teacher']);
        return $this->render('class_subject/semclass_subject.html.twig', [
            'controller_name' => 'ClassSubjectController','ct'=>$ct,'findsubject'=>$findsubject,'findteacher'=>$findteacher
        ]);
    }



           /**
             * @Route("/class/action/subject/{cm}", name="think_class")
             */
        public function SubAction(Request $request,$cm): Response
        {
            $em=$this->getDoctrine()->getManager();
            $subjecty= new SubjectYear();
            $subject1=$request->request->get('subject');
            $teacher1=$request->request->get('teacher');
            $year1=$request->request->get('year');
            $findteacher1 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$teacher1]);
            $findteacher =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'teacher']);
            $findshow =$this->getDoctrine()->getRepository(Subject::class)->findOneBy(['name'=>$subject1]);

             $connection=$em->getConnection();
            $statement133=$connection->prepare("
              SELECT subject.name FROM `subject`");
            $statement133->execute();
            $findsubject=$statement133->fetchAll();

             $findc =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['name'=>$cm]);
             $IP=$findc->getId();

            if($request->isMethod('post'))
            {
              if(!empty($subject1) & !empty($teacher1) & !empty($year1))
               {
               if($findsubject )
               {
                 if($findteacher1)
		         {
                     $subjecty->setSubjectname($findshow);
                     $subjecty->setSteacher($findteacher1);
                     $subjecty->addAddclass($findc);
                     $subjecty->setSyear($year1);
                     $subjecty->setSemester('semester1');
                     $em->persist($subjecty);
                     $em->flush();
                     return $this->redirect($this->generateUrl('class_semester_subject',['id'=>$IP]));
                 }
                $this->addFlash('teacher','teacher name not found');
                 return $this->render('class_subject/class_subject.html.twig',['findteacher'=>$findteacher,'findsubject'=>$findsubject]);
                }
                $this->addFlash('subject','Subject name not found');
                 return $this->render('class_subject/class_subject.html.twig',['findteacher'=>$findteacher,'findsubject'=>$findsubject]);
                }
               else
                {
                $this->addFlash('just','All field are required');
                 return $this->render('class_subject/class_subject.html.twig',['findteacher'=>$findteacher,'findsubject'=>$findsubject]);
                }

            }


    }

           /**
             * @Route("/class/action/subject/semester/two/{cm}", name="try_class")
             */
        public function SemesterAction(Request $request,$cm): Response
        {
            $em=$this->getDoctrine()->getManager();
            $subjecty= new SubjectYear();
            $subject1=$request->request->get('subject');
            $teacher1=$request->request->get('teacher');
            $year1=$request->request->get('year');
            $findteacher1 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$teacher1]);
            $findteacher =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'teacher']);
            $findshow =$this->getDoctrine()->getRepository(Subject::class)->findOneBy(['name'=>$subject1]);

                $connection=$em->getConnection();
            $statement133=$connection->prepare("
              SELECT subject.name FROM `subject`
                ");
            $statement133->execute();
            $findsubject=$statement133->fetchAll();

             $findc =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['name'=>$cm]);
             $IP=$findc->getId();

            if($request->isMethod('post'))
            {
              if(!empty($subject1) & !empty($teacher1) & !empty($year1))
               {
               if($findsubject )
               {
                 if($findteacher1)
		         {
                     $subjecty->setSubjectname($findshow);
                     $subjecty->setSteacher($findteacher1);
                     $subjecty->addAddclass($findc);
                     $subjecty->setSyear($year1);
                     $subjecty->setSemester('semester2');
                     $em->persist($subjecty);
                     $em->flush();
                     return $this->redirect($this->generateUrl('semester2_subject',['id'=>$IP]));
                 }
                $this->addFlash('teacher','teacher name not found');
                 return $this->render('class_subject/semclass_subject.html.twig',['findteacher'=>$findteacher,'findsubject'=>$findsubject]);
                }
                $this->addFlash('subject','Subject name not found');
                 return $this->render('class_subject/semclass_subject.html.twig',['findteacher'=>$findteacher,'findsubject'=>$findsubject]);
                }
               else
                {
                $this->addFlash('just','All field are required');
                 return $this->render('class_subject/semclass_subject.html.twig',['findteacher'=>$findteacher,'findsubject'=>$findsubject]);
                }

            }
    }



     /**
     * @Route("/dashboard/update/student/marks/{id}/{cm}/{y}",name="editmarks")
     */
public function editmarks(Request $request,$id,$cm,$y)
{
        $result =$this->getDoctrine()->getRepository(Result::class)->findOneBy(['id' => $id]);
        $cid =$this->getDoctrine()->getRepository(Subject::class)->findOneBy(['name' => $cm]);
        $cid=$cid->getId();

        $form = $this->createFormBuilder($result)
        ->add('test1',IntegerType::class, array('label'=>'Test one marks','attr' => array('class' => 'form-control','required'=>false)))
        ->add('test2',IntegerType::class, array('label'=>'Test two marks','attr' => array('class' => 'form-control','required'=>false)))
        ->add('individual1',IntegerType::class, array('label'=>'Individual assignment 1','attr' => array('class' => 'form-control','required'=>false)))
        ->add('individual2',IntegerType::class, array('label'=>'individual assigment 2','attr' => array('class' => 'form-control','required'=>false)))
        ->add('groupWork',IntegerType::class, array('label'=>'Group Work','attr' => array('class' => 'form-control','required'=>false)))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
            $totalmark =40;
            $test1=$form->get('test1')->getData();
            $test2=$form->get('test2')->getData();
            $individual1=$form->get('individual1')->getData();
            $individual2=$form->get('individual2')->getData();
            $group_work=$form->get('groupWork')->getData();
            $em = $this->getDoctrine()->getManager();
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
                    $em->persist($result);
                    $em->flush();
                   return $this->redirect($this->generateUrl('add_result_class',['id'=>$cid,'y'=>$y]));

                  }
                 else
                  {
                    $this->addFlash('subjectmark','error, ....total mark above 40');
                   return $this->render('security/editresult.html.twig',array('form'=>$form->createView()));
                  }
              }
        return $this->render('security/editresult.html.twig',array('form'=>$form->createView()

                ));
 }

}
