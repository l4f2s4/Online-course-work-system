<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Subject;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Result;
use App\Entity\User;
use App\Entity\Student;

class RemoveController extends AbstractController
{


/**
     * @Route("/engine/admin/remove/admin/{id}", name="removeadmin")
     */
    public function removeadmin(Request $request,$id): Response
    {

            $user1=$this->getUser();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
                          //symfony sql statement to print teacher details
                    $decl="SELECT user.id,user.username,user.email,user.gender,user.martalstatus,
                                user.title,user.nationality
                    FROM  `user`
                    where title='administrator' or title='superadmin'  ";

                    $statement23=$connection->prepare($decl);
                    $statement23->execute();
                    $teacher=$statement23->fetchAll();
                    $userid=$this->getUser()->getId();
                    $projectuser =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);

            if($projectuser){
                    $em->remove($projectuser);
                    $em->flush();
                    return $this->redirectToRoute('add_admin');
            }
            else if($userid==$id){
                    $session = $this->get('session');
                    $session = new Session();
                    $session->invalidate();
                    $em->remove($projectuser);
                    $em->flush();
                    $success="Your account no longer exists contact administrator for more info";
                    return new response($success);

            }
            else{
                    return $this->render('superuser/addadmin.html.twig',['teacher'=>$teacher]);
                 }


    }
     /**
     * @Route("/{id}/edit/name",name="edit")
     */
public function edit(Request $request,$id)
{

        $department4 =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($department4)

        ->add('name',TextType::class, array('label'=>'Class Name','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('add_class'));
        }
        return $this->render('security/editclass.html.twig',array('form'=>$form->createView()

                ));
}

     /**
     * @Route("/{id}/edit/department/name",name="editd")
     */
public function editd(Request $request,$id)
{

        $department4 =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($department4)

        ->add('name',TextType::class, array('label'=>'Department Name','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('add_department'));
        }
        return $this->render('security/editdepartment.html.twig',array('form'=>$form->createView()

                ));
}

    /**
     * @Route("/{id}/edit/{idn}/class/subject/name",name="edits")
     */
public function edits(Request $request,$id,$idn)
{

        $department4 =$this->getDoctrine()->getRepository(Subject::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($department4)

        ->add('name',TextType::class, array('label'=>'Subject Name','attr' => array('class' => 'form-control')))

        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('class_semester_subject',['id'=>$idn]));
        }
        return $this->render('security/editsubject.html.twig',array('form'=>$form->createView()

                ));
}
    /**
     * @Route("/{id}/edit/{idn}/class/subject/semester/two/name",name="semesteredits")
     */
public function semesteredits(Request $request,$id,$idn)
{

        $department4 =$this->getDoctrine()->getRepository(Subject::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($department4)

        ->add('name',TextType::class, array('label'=>'Subject Name','attr' => array('class' => 'form-control')))

        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('semester2_subject',['id'=>$idn]));
        }
        return $this->render('security/editsubject.html.twig',array('form'=>$form->createView()

                ));
}

        /**
     * @Route("/engine/admin/area/{id}/edit/name",name="editadmin")
     */
public function editpost(Request $request,$id)
{
        $student=new Student();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('title',TextType::class, array('label'=>'Roles','attr' => array('class' => 'form-control')))
        ->add('phoneno',TelType::class, array('label'=>'Phone no','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('martalstatus',TextType::class, array('label'=>'Marital status','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('add_admin'));
        }
        return $this->render('security/editpost.html.twig',array('form'=>$form->createView()

                ));
    }

            /**
     * @Route("/engine/admin/area/teacher/{id}/edit/name",name="editteacher")
     */
public function editteacher(Request $request,$id)
{
        $student=new Student();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Username','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('title',TextType::class, array('label'=>'Roles','attr' => array('class' => 'form-control')))
        ->add('phoneno',TelType::class, array('label'=>'Phone no','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('martalstatus',TextType::class, array('label'=>'Marital status','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('add_teacher'));
        }
        return $this->render('security/editpost.html.twig',array('form'=>$form->createView()

                ));
    }

            /**
     * @Route("/engine/admin/area/student/{id}/edit/info/detail/{y}",name="editstudent")
     */
public function editstudent(Request $request,$id,$y)
{
        $user4 =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('name',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('regno',TextType::class, array('label'=>'Registration no','attr' => array('class' => 'form-control')))
        ->add('phoneno',TelType::class, array('label'=>'Phone no','attr' => array('class' => 'form-control')))
       ->add('YoS',IntegerType::class, array('label'=>'Year of study','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
             ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('add_student',['y'=>$y]));
        }
        return $this->render('security/editstudent.html.twig',array('form'=>$form->createView()

                ));
    }


   /**
     * @Route("/user/profile/area/update/info/detail",name="my_profile")
     */
public function editprofile(Request $request)
{
        $user=$this->getUser();
        $id=$user->getId();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('phoneno',TelType::class, array('label'=>'Phone no','attr' => array('class' => 'form-control')))
        ->add('userimage',FileType::class, array('label'=>' Upload Profile picture','mapped'=>false,
        'data_class'=>User::class,'required'=>false,'attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('martalstatus',TextType::class, array('label'=>'Marital status','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
                    // we must transform the image string from Db  to File to respect the form types
              $oldFileName = $user4->getUserimage();
                   /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['userimage']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('photos_directory');

                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                  try {
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                }catch(FileException $e){
                }
                $user4->setUserimage($newFilename);
            }
            else{
                 $user4->setUserimage($oldFileName );
            }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('my_profile'));
        }
        return $this->render('security/editprofile.html.twig',array('form'=>$form->createView()

                ));
    }

}
