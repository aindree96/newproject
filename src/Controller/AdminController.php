<?php

namespace App\Controller;


use App\Entity\Question;
use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin" , name="admin")
     * @IsGranted("ROLE_ADMIN")
     */

    public function admin()
    {
        return $this->render('admin/adminhome.html.twig');

    }

     /**
     * @Route("new/question" , name="app_question")
     * @IsGranted("ROLE_ADMIN")
     */

    public function addQuestion(){

        return $this->render('exam/addquestion.html.twig');

    }

    /**
     * @Route("new/question/added",name="app_question_add_database",methods="POST")
     */

    public function addQuestionsToDatabase(EntityManagerInterface $entityManager,Request $request){
        
        $question = $request->get('addquestion');
        $wronganswer1 = $request->get('wronganswer1');
        $wronganswer2 = $request->get('wronganswer2');
        $wronganswer3 = $request->get('wronganswer3');
        $correctanswer = $request->get('correctanswer');

        $answers = [ $wronganswer1 , $wronganswer2 , $wronganswer3 , $correctanswer ];

        $questionObj = new Question();
        $questionObj->setQuestion($question);
        
        foreach($answers as $answer){
            $answerObj = new Answer();
            $answerObj -> setAnswer($answer);
            $answerObj->setQuestion($questionObj);
            $entityManager->persist($answerObj);
        }
        $questionObj->setCorrectanswer($answerObj);
        $answerObj->setQuestion($questionObj);
        $entityManager->persist($questionObj);
        
        $samequestion=$entityManager->getRepository(Question::class)->findBy(['question'=>$question]);
        if ($samequestion) {
            $this->addFlash('error',"Question Already Present");
            return $this->redirectToRoute('app_question');
        } else { 
            $entityManager->flush();
            $this->addFlash('success',"Question is added successfully");
            return $this->redirectToRoute('app_question');
        }
    }

     /**
     * @Route("show/question" , name="app_show_question")
     * @IsGranted("ROLE_ADMIN")
     */
    public function showQuestion(EntityManagerInterface $entityManager){
        $questions=$entityManager->getRepository(Question::class)->findAll();
       
        $answers=$entityManager->getRepository(Answer::class)->findAll();
      
        return $this->render('exam/showquestion.html.twig',[
            'questions'=>$questions,
            'answers'=>$answers
        ]);
    }

     /**
     * @Route ("/delete/{id}",name="app_question_delete")
     */

    public function delete($id,EntityManagerInterface $entityManager){
        $question=$entityManager->getRepository(Question::class)->findOneBy(['id'=>$id]);
        $entityManager->remove($question);
        $entityManager->flush();
        $this->addFlash('success',"Question deleted successfully");
        return $this->redirectToRoute('app_show_question');
    }

  
     /**
     * @Route ("/edit/{id}",name="app_question_edit")
     */

    public function edit($id,EntityManagerInterface $entityManager){
        $question=$entityManager->getRepository(Question::class)->findOneBy(['id'=>$id]);
        return $this->render('exam/edit.html.twig',[
            'question'=>$question
        ]);

    }

     /**
     * @Route("new/question/updated/{id}",name="app_question_update_database",methods="POST")
     */

    public function updateQuestionsToDatabase($id,EntityManagerInterface $entityManager,Request $request){
        $questionname = $request->get('addquestion');
        $wronganswer1 = $request->get('wronganswer1');
        $wronganswer2 = $request->get('wronganswer2');
        $wronganswer3 = $request->get('wronganswer3');
        $wronganswer4 = $request->get('wronganswer4');
        $correct = $request->get('correctanswer');

        $question=$entityManager->getRepository(Question::class)->findOneBy(['id'=>$id]);
        $question->setQuestion($questionname);
        $question->setWrong1($wronganswer1);
        $question->setWrong2($wronganswer2);
        $question->setWrong3($wronganswer3);
        $question->setWrong4($wronganswer4);
        $question->setAnswer($correct);
      
        $entityManager->flush();
        $this->addFlash('success',"Question is updated successfully");
        return $this->redirectToRoute('app_show_question');
        
    }

}
