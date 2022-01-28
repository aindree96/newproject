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
     * @IsGranted("ROLE_ADMIN")
     */

    #[Route('/admin',name: 'admin')]
    public function admin()
    {
        return $this->render('admin/adminhome.html.twig');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */

    #[Route('/new/question',name: 'app_question')]
    public function addQuestion(){
        return $this->render('exam/addquestion.html.twig');
    }

    #[Route('new/question/added',name: 'app_question_add_database',methods:'POST')]
    public function addQuestionsToDatabase(EntityManagerInterface $entityManager,Request $request){

        $question = $request->get('question');
        $firstWrongAnswer = $request->get('wronganswer1');
        $secondWrongAnswer = $request->get('wronganswer2');
        $thirdWrongAnswer = $request->get('wronganswer3');
        $correctAnswer = $request->get('correctanswer');

        $arrayOfFetchedAnswers = [ $firstWrongAnswer , $secondWrongAnswer , $thirdWrongAnswer , $correctAnswer ];

        $questionObject = new Question();
        $questionObject->setQuestion($question);

        foreach($arrayOfFetchedAnswers as $answer){
            $answerObject = new Answer();
            $answerObject->setAnswer($answer);
            $answerObject->setQuestion($questionObject);
            $entityManager->persist($answerObject);
          }

        $questionObject->setCorrectanswer($answerObject);   
        $entityManager->persist($questionObject);     

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
     * @IsGranted("ROLE_ADMIN")
     */

    #[Route('/show/question',name: 'app_show_question')]
    public function showQuestions(EntityManagerInterface $entityManager){
        $questions=$entityManager->getRepository(Question::class)->findAll();
        $answers=$entityManager->getRepository(Answer::class)->findAll();

        return $this->render('exam/showquestion.html.twig',[
            'questions'=>$questions,
            'answers'=>$answers
        ]);
    }
}
