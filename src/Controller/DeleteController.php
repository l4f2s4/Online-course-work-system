<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Subject;
use App\Entity\SubjectYear;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Result;
use App\Entity\User;
use App\Entity\Student;

class DeleteController extends AbstractController
{
    /**
     * @Route("/engine/delete/student/{y}/{id}", name="delete")
     */
    public function removestudent(Request $request,$id,$y): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                if($user1!=null){
                        $undergraduate =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id' => $id]);
                if($undergraduate){
                        $i=$undergraduate->getId();
                        $red =$this->getDoctrine()->getRepository(Result::class)->findBy(['StundentNameR' => $i]);
                        if($red){
                         foreach($red as $i)
                         {
                         $undergraduate->removeAddResultStudent($i);
                         $em->remove($undergraduate);
                         }
                        }
                        $undergraduate->setAddStudentClass(null);
                        $undergraduate->setSdept(null);
                        $em->remove($undergraduate);
                        }
                        $em->flush();
                     return $this->redirectToRoute('add_student',['y'=>$y]);
                }
                else{
                          return $this->redirectToRoute('just');
                   }
    }


       /**
     * @Route("/engine/delete/teacher/{id}", name="deleteteacher")
     */
    public function removeteacher($id): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                if($user1!=null){
              $teacher =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
              $sub =$this->getDoctrine()->getRepository(SubjectYear::class)->findBy(['steacher' => $id]);


                if($teacher){

                         $teacher->setAddDepartment(null);

                  if($sub){
                  foreach($sub as $i){
                         $l=$i->getId();
                         $d =$this->getDoctrine()->getRepository(SubjectYear::class)->findOneBy(['id' => $l]);
                         $teacher->removeSubjy($d);
                         $em->remove($teacher);
                        }

                      }
                  }
                 $em->remove($teacher);
                 $em->flush();
                 return $this->redirectToRoute('add_teacher');
                }
                else{
                          return $this->redirectToRoute('just');
                   }
    }
        /**
     * @Route("/engine/delete/Department/show/{id}", name="deletedept")
     */
    public function removedept(Request $request,$id): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                if($user1!=null){
                        $department =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id' => $id]);
                if($department){
                        $cid=$department->getId();
                        $dep =$this->getDoctrine()->getRepository(Course::class)->findBy(['deptclass' => $cid]);
                        $student =$this->getDoctrine()->getRepository(Student::class)->findBy(['sdept' => $cid]);
                        $user =$this->getDoctrine()->getRepository(User::class)->findBy(['addDepartment' => $cid]);
                        if($dep){
                        foreach($dep as $i){
                         $l=$i->getId();
                         $d =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $l]);
                         $department->removeRclass($d);

                        }
                        }
                         if($user)
                         {
                        foreach($user as $i){
                         $l=$i->getId();
                         $d =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $l]);
                         $department->removeAssignUser($d);

                        }

                        }
                        if($student){
                         foreach($student as $i){
                         $l=$i->getId();
                         $d =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id' => $l]);
                         $d->setSdept(null);

                        }
                        }

                        $em->remove($department);
                        $em->flush();
                     return $this->redirectToRoute('add_department');
                }
                }
                else{
                          return $this->redirectToRoute('just');
                   }
    }
}
