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
use App\Entity\User;


class MainEngineController extends AbstractController
{

      /**Result function used to add,display,update,delete result*/
     /**
     * @Route("/dashboard/published/result", name="add_result")
     */
    public function result()
    {
     $em=$this->getDoctrine()->getManager();
    $connection=$em->getConnection();
    $findc =$this->getDoctrine()->getRepository(Subject::class)->findAll();
    $user=$this->getUser();
    $uid=$user->getId();
     //symfony sql statement to print subject details at most
    $decl12=" SELECT name FROM subject limit 2";
    $statement931=$connection->prepare($decl12);
    $statement931->execute();
    $sub=$statement931->fetchAll();
    $decl120= "SELECT subject.name,subject_year.id as ID,subject_year.syear FROM `subject` inner join subject_year on
              subject.id=subject_year.subjectname_id inner join
              user on subject_year.steacher_id=user.id where title='teacher' and user.id=:id";
    $statement933=$connection->prepare($decl120);
    $statement933->bindParam(':id',$uid);
    $statement933->execute();
    $findsubject=$statement933->fetchAll();


    //symfony sql statement to print class  details along with department


    return $this->render('main_engine/result.html.twig',['ST'=>$findsubject,'findc'=>$findc]);

    }

      /**result show function used to add,display,update,delete result*/
     /**
     * @Route("/published/result/ca/{id}/{y}", name="add_result_class")
     */
    public function CA($id,$y)
    {
     $em=$this->getDoctrine()->getManager();
     $connection=$em->getConnection();
    //symfony sql statement to print subject
    $decl12="SELECT name
        FROM
         `subject` inner join subject_year on subject.id=subject_year.subjectname_id
        where subject_year.id=:idc;
    ";
    $statement97=$connection->prepare($decl12);
    $statement97->bindParam(':idc', $id);
    $statement97->execute();
    $cm=$statement97->fetchOne();
    $statement2=$connection->prepare("SELECT count(*) FROM result inner join
      result_subject_year on
    result.id=result_subject_year.result_id WHERE total < 16 and result_subject_year.subject_year_id=:inu ");
   $statement2->bindParam(':inu',$id);
   $statement2->execute();
   $belowca=$statement2->fetchOne();
   $statement24=$connection->prepare("SELECT count(*) FROM result inner join
      result_subject_year on
    result.id=result_subject_year.result_id WHERE total > 16 and result_subject_year.subject_year_id=:inu ");
   $statement24->bindParam(':inu',$id);
   $statement24->execute();
   $aboveca=$statement24->fetchOne();

    //symfony sql statement to print subject
    $decl=" SELECT result.id,student.regno,student.name,subject_year.id as ID
        FROM `subject_year`
        inner join result_subject_year
        on
        subject_year.id=result_subject_year.subject_year_id
        inner join `result`
        on result_subject_year.result_id=result.id
        inner join  `student`
        on result.stundent_name_r_id=student.id where subject_year.id=:idnam and result.addstatus='notadded' order by student.name
    ";
    $statement93=$connection->prepare($decl);
    $statement93->bindParam(':idnam', $id);
    $statement93->execute();
    $rt=$statement93->fetchAll();

    //count

      $decl45=" SELECT count(*)
        FROM `subject_year`
        inner join result_subject_year
        on subject_year.id=result_subject_year.subject_year_id
        inner join `result`
        on result_subject_year.result_id=result.id
        inner join  `student`
        on result.stundent_name_r_id=student.id where subject_year.id=:idnam and result.addstatus='published' and result.signstatus='problem'";
    $statement9356=$connection->prepare($decl45);
    $statement9356->bindParam(':idnam', $id);
    $statement9356->execute();
    $prt=$statement9356->fetchOne();

     $decl55=" SELECT count(*)
        FROM `subject_year`
        inner join result_subject_year
        on subject_year.id=result_subject_year.subject_year_id
        inner join `result`
        on result_subject_year.result_id=result.id
        inner join  `student`
        on result.stundent_name_r_id=student.id where subject_year.id=:idnam and result.addstatus='published' and result.signstatus='signed'";
    $statement956=$connection->prepare($decl55);
    $statement956->bindParam(':idnam', $id);
    $statement956->execute();
    $srt=$statement956->fetchOne();

    //endcount

    //symfony sql statement to print subject
    $decl123="SELECT result.id,student.regno,student.name,result.test1,result.test2,
        result.individual1,result.individual2,result.group_work,
        result.total,result.percent
        FROM `subject_year`
        inner join result_subject_year
        on subject_year.id=result_subject_year.subject_year_id
        inner join `result`
        on result_subject_year.result_id=result.id
        inner join  `student`
        on result.stundent_name_r_id=student.id where subject_year.id=:idnam and result.addstatus='added'  order by result.total desc
    ";
    $statement931=$connection->prepare($decl123);
    $statement931->bindParam(':idnam', $id);
    $statement931->execute();
    $jt=$statement931->fetchAll();


     $decl124="SELECT result.id,student.regno,student.name,result.test1,result.test2,
        result.individual1,result.individual2,result.group_work,
        result.total,result.percent
        FROM `subject_year`
        inner join result_subject_year
        on subject_year.id=result_subject_year.subject_year_id
        inner join `result`
        on result_subject_year.result_id=result.id
        inner join  `student`
        on result.stundent_name_r_id=student.id where subject_year.id=:idnam and result.addstatus='published'  order by result.total desc;
    ";
    $statement933=$connection->prepare($decl124);
    $statement933->bindParam(':idnam', $id);
    $statement933->execute();
    $pt=$statement933->fetchAll();


    return $this->render('main_engine/resultsubject.html.twig',['prt'=>$prt,'srt'=>$srt,'belowca'=>$belowca,'aboveca'=>$aboveca,'rt'=>$rt,'cm'=>$cm,'id'=>$id,'year'=>$y,'jt'=>$jt,'pt'=>$pt]);

    }
     /**result show year function used to add,display,update,delete result*/
     /**
     * @Route("/published/result/year/ca/{id}", name="add_year_class")
     */
    public function Cyear($id)
    {
     $em=$this->getDoctrine()->getManager();
     $connection=$em->getConnection();
    //symfony sql statement to print subject
    $decl12="SELECT name
        FROM
         `subject`
        where subject.id=:idc;
    ";
    $statement97=$connection->prepare($decl12);
    $statement97->bindParam(':idc', $id);
    $statement97->execute();
    $cm=$statement97->fetchOne();

    //symfony sql statement to print subject
    $decl="SELECT DISTINCT subject.id as ID,user.username as Ny,subject_year.syear
     FROM  `subject`
     inner join `subject_year` on subject.id=subject_year.subjectname_id
    inner join `user`
    on subject_year.steacher_id=user.id
     where user.title='teacher' and subject.id=:idnam
    ";
    $statement93=$connection->prepare($decl);
    $statement93->bindParam(':idnam', $id);
    $statement93->execute();
    $rt=$statement93->fetchAll();

    return $this->render('addAction/subjectyear.html.twig',['rn'=>$rt,'cm'=>$cm,'cmid'=>$id]);

    }

      /**result show function used to add,display,update,delete result*/
     /**
     * @Route("/published/ca/result/student/out/{id}/{y}", name="ca_result_class")
     */
    public function CS($id,$y)
    {
     $em=$this->getDoctrine()->getManager();
     $connection=$em->getConnection();
    //symfony sql statement to print subject
    $decl12="SELECT name
        FROM
         `subject` inner join subject_year on subject.id=subject_year.subjectname_id
        where subject_year.id=:idc;
    ";
    $statement97=$connection->prepare($decl12);
    $statement97->bindParam(':idc', $id);
    $statement97->execute();
    $cm=$statement97->fetchOne();

     $decl121="SELECT syear
     FROM `subject`
     INNER JOIN subject_year
     ON subject.id=subject_year.subjectname_id
     where subject_year.id=:t and subject_year.syear=:year
    ";
    $statement971=$connection->prepare($decl121);
    $statement971->bindParam(':t', $id);
    $statement971->bindParam(':year', $y);
    $statement971->execute();
    $cyear=$statement971->fetchOne();

    //symfony sql statement to print subject
    $decl=" SELECT result.id,student.regno,student.name,result.test1,result.test2,
        result.individual1,result.individual2,result.group_work,
        result.total,result.percent,result.reported
        FROM `subject_year`
        inner join result_subject_year
        on subject_year.id=result_subject_year.subject_year_id
        inner join `result`
        on result_subject_year.result_id=result.id
        inner join  `student`
        on result.stundent_name_r_id=student.id where subject_year.id=:idnam and result.addstatus='published' and result.signstatus='problem' order by result.total desc";
    $statement93=$connection->prepare($decl);
    $statement93->bindParam(':idnam', $id);
    $statement93->execute();
    $rt=$statement93->fetchAll();

    return $this->render('addAction/problemreported.html.twig',['rt'=>$rt,'cm'=>$cm,'id'=>$id,'year'=>$y]);

    }



      /**result show function used to add,display,update,delete result*/
     /**
     * @Route("/published/ca/result/student/signed/out/{id}/{y}", name="ca_signed_class")
     */
    public function Sign($id,$y)
    {
     $em=$this->getDoctrine()->getManager();
     $connection=$em->getConnection();
    //symfony sql statement to print subject
    $decl12="SELECT name
        FROM
         `subject` inner join subject_year on subject.id=subject_year.subjectname_id
        where subject_year.id=:idc;
    ";
    $statement97=$connection->prepare($decl12);
    $statement97->bindParam(':idc', $id);
    $statement97->execute();
    $cm=$statement97->fetchOne();

     $decl121="SELECT syear
     FROM `subject`
     INNER JOIN subject_year
     ON subject.id=subject_year.subjectname_id
     where subject_year.id=:t and subject_year.syear=:year
    ";
    $statement971=$connection->prepare($decl121);
    $statement971->bindParam(':t', $id);
    $statement971->bindParam(':year', $y);
    $statement971->execute();
    $cyear=$statement971->fetchOne();

    //symfony sql statement to print subject
    $decl=" SELECT student.regno,student.name,result.test1,result.test2,
        result.individual1,result.individual2,result.group_work,
        result.total,result.percent
        FROM `subject_year`
        inner join result_subject_year
        on subject_year.id=result_subject_year.subject_year_id
        inner join `result`
        on result_subject_year.result_id=result.id
        inner join  `student`
        on result.stundent_name_r_id=student.id where subject_year.id=:idnam and result.addstatus='published' and result.signstatus='signed' order by result.total desc";
    $statement93=$connection->prepare($decl);
    $statement93->bindParam(':idnam', $id);
    $statement93->execute();
    $rt=$statement93->fetchAll();

    return $this->render('teacher/signedca.html.twig',['rt'=>$rt,'cm'=>$cm,'year'=>$y]);

    }

         /**update password */
     /**
     * @Route("/update/user/password", name="app_password")
     */
    public function pass()
    {
     return $this->render('main_engine/changepassword.html.twig');

    }

        /**
        * @Route("/dashboard/setting/security/updpass",name="app_update_pass")
        */
    public function updpass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
       $oldpass=strip_tags($request->request->get('oldpass'));
       $user=$this->getUser();
       $encold= $passwordEncoder->isPasswordValid($user,$oldpass);
       $newpass=strip_tags($request->request->get('newpass'));
       $confirm=strip_tags($request->request->get('conf'));
          if($request->isMethod('POST') && !empty($oldpass) && !empty($newpass) && !empty($confirm))
            {
               if($encold==true)
                  {
                      if(strlen($newpass)>7)
                        {
                             if($newpass==$confirm)
                                 {
                                    $conf=$passwordEncoder->encodePassword($user,$confirm);
                                    $user->setPassword($conf);
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $entityManager->persist($user);
                                    $entityManager->flush();
                                     $session = $this->get('session');
                                     $session = new Session();
                                     $session->invalidate();
                                     return $this->redirect($this->generateUrl('main_engine'));
                                 }
                             else
                                 {
                                      $this->addFlash('mismatch',  'Password mismatch!');
                                      return $this->render('main_engine/changepassword.html.twig');
                                  }
                        }
                      else
                        {

                                    return $this->render('main_engine/changepassword.html.twig');
                         }
                  }
                  else
                  {
                        $this->addFlash('current','The current password is incorrect');
                        return $this->render('main_engine/changepassword.html.twig');
                  }

            }
          else
           {

                  return $this->render('main_engine/changepassword.html.twig');
          }

    }

    

}
