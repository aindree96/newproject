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
use App\Service\MessageGenerator;
use App\Service\InsertQuestion;

class AdminController extends AbstractController
{
  
    #[Route('/admin',name: 'admin')]
    #[IsGranted("ROLE_ADMIN")]
    public function admin()
    {
        return $this->render('admin/adminhome.html.twig');
    }

    #[Route('/add-question',name: 'add_question')]
    #[IsGranted("ROLE_ADMIN")]
    public function addQuestion()
    {
        return $this->render('exam/addquestion.html.twig');
    }

    #[Route('insert-questions',name: 'insert_questions',methods:'POST')]
    public function addQuestionsToDatabase(EntityManagerInterface $entityManager,Request $request,MessageGenerator $messageGenerator,InsertQuestion $insertQuestion)
    {
        $question = $request->get('question');
        $arrayOfFetchedAnswers = [ $request->get('wronganswer1') , $request->get('wronganswer2') , $request->get('wronganswer3') , $request->get('correctanswer') ];
        $insertQuestion->setQuestion($question,$arrayOfFetchedAnswers,$entityManager);
        
        $samequestion=$entityManager->getRepository(Question::class)->findBy(['question'=>$question]);
        if ($samequestion) {
            $this->addFlash('error',"Question Already Present");          
        } else {
            $entityManager->flush();
            $message = $messageGenerator->getSuccessMessage();
            $this->addFlash('success', $message);
        }
        return $this->redirectToRoute('add_question');
    }

    #[Route('/show-questions',name: 'show_questions')]
    #[IsGranted("ROLE_ADMIN")]
    public function showQuestions(EntityManagerInterface $entityManager)
    {
        $questions=$entityManager->getRepository(Question::class)->findAll();
        return $this->render('exam/showquestion.html.twig',[
            'questions'=>$questions          
        ]);
    }
}
