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
class AdminEngineController extends AbstractController
{
   /** administrator dashboard function*/
    /**
     * @Route("/engine", name="admin_engine")
     */
    public function admin(): Response
    {
        $user1=$this->getUser();
           if($user1 != null)
          {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        //symfony doctrine handle
        $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
        $findclass =$this->getDoctrine()->getRepository(Course::class)->findAll();
        $findstudent =$this->getDoctrine()->getRepository(Student::class)->findAll();


        //symfony sql statement for counting ca above the limit
        $statement1=$connection->prepare("SELECT count(*) FROM result WHERE total >= 16");
        $statement1->execute();
        $aboveca=$statement1->fetchOne();

        $statement13=$connection->prepare("SELECT count(*) FROM subject_year");
        $statement13->execute();
        $findsubject=$statement13->fetchOne();


        //symfony sql statement for counting ca below the limit
        $statement2=$connection->prepare("SELECT count(*) FROM result WHERE total < 16");
        $statement2->execute();
        $belowca=$statement2->fetchOne();


        //symfony sql statement for counting how many student ... signed the CA
        $statement3=$connection->prepare("SELECT count(*) FROM result WHERE signstatus='signed'");
        $statement3->execute();
        $signedca=$statement3->fetchOne();

        //symfony sql statement for counting how many administrator ...registered
        $statement33=$connection->prepare("SELECT count(*) FROM user WHERE title='teacher'");
        $statement33->execute();
        $SA=$statement33->fetchOne();

        //symfony sql statement for counting how many student ... CA Problem
        $statement4=$connection->prepare("SELECT count(*) FROM result WHERE signstatus='problem'");
        $statement4->execute();
        $problem=$statement4->fetchOne();

        return $this->render('superuser/admindashboard.html.twig', [
            'controller_name' => 'AdminEngineController','finddept'=>$finddept,'findclass'=>$findclass,
            'findstudent'=>$findstudent,'findsubject'=>$findsubject,'above'=>$aboveca,'below'=>$belowca,
            'signed'=>$signedca,'problem'=>$problem,'SA'=>$SA
        ]);
         }else
         {
                    return $this->redirectToRoute('just');
          }

    }

    /**This function allow administrator to Add,display,delete,edit  Administrator To the System*/

    /**
     * @Route("/engine/admin", name="add_admin")
     */
    public function add()
    {
        $user=$this->getUser();

           if($user != null)
          {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();

        //symfony sql statement to print administrator details
        $decl=" SELECT user.id,user.username,user.email,user.gender,user.martalstatus,user.title,user.nationality
        FROM  `user`
        where title='administrator' or title='superadmin' " ;

        $statement13=$connection->prepare($decl);
        $statement13->execute();
        $display=$statement13->fetchAll();
        return $this->render('superuser/addadmin.html.twig',['display'=>$display]);
        }else
         {
                    return $this->redirectToRoute('just');
          }
    }

    /**This function allow administrator to Add,display,delete,edit Teachers To the System*/

    /**
     * @Route("/engine/admin/teacher", name="add_teacher")
     */
    public function teacher()
    {
        $user=$this->getUser();
        if($user != null){
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();

        //symfony sql statement to print teacher details
        $decl=" SELECT user.id,user.username,user.email,user.gender,user.martalstatus,user.nationality
        FROM  `user`
        where title='teacher' ";

        $statement23=$connection->prepare($decl);
        $statement23->execute();
        $teacher=$statement23->fetchAll();

        return $this->render('superuser/teachers.html.twig',['teacher'=>$teacher]);
        }else
         {
                    return $this->redirectToRoute('just');
          }
    }
      /**
     * @Route("/engine/admin/department/member/teacher/{id}", name="member")
     */
    public function member($id)
    {
        $user=$this->getUser();
        if($user != null){
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();

        $findname =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id'=>$id]);
        $name=$findname->getName();
        //symfony sql statement to print teacher details
        $decl=" SELECT user.id,user.username,user.email,user.gender,user.martalstatus,user.nationality
        from `user`
         where title='teacher' and user.add_department_id=:id ";

        $statement23=$connection->prepare($decl);
        $statement23->bindParam(':id',$id);
        $statement23->execute();
        $teacher=$statement23->fetchAll();

        return $this->render('security/member.html.twig',['teacher'=>$teacher,'name'=>$name]);
        }
        else
         {
                    return $this->redirectToRoute('just');
          }
    }

    /**This function allow administrator to Add,display,delete,edit Teachers To the System*/

    /**
     * @Route("/engine/admin/student/content/yos/{y}", name="add_student")
     */
    public function student($y)
    {
        $user=$this->getUser();
        if($user != null){
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();

        //symfony doctrine
        $cout =$this->getDoctrine()->getRepository(Student::class)->findAll();

        //symfony sql statement to print student details
        $decl=" SELECT student.id,student.name as NM,student.nationality,student.regno,student.email,student.gender,course.name as CN
        FROM  `student`
        inner join `course`
        on course.id=student.add_student_class_id where student.yo_s=:year";

        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':year',$y);
        $statement12->execute();
        $student=$statement12->fetchAll();

        return $this->render('superuser/student.html.twig',['student'=>$student,'cout'=>$cout,'year'=>$y]);
       }else
         {
                    return $this->redirectToRoute('just');
          }
    }
}
