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

class examController extends AbstractController
{
    #[Route('/',name: 'homepage')]
    public function homepage(){
        return $this->render('front/homepage.html.twig');
    }

    #[Route('/exam-process',name: 'exam_process', methods:'POST')]
    public function examProcess(Request $request,EntityManagerInterface $entityManager){
        $fetchedQuestions = $entityManager->getRepository(Question::class)->findAll();
        $score=0;
        $selectedAnswers = $request->get('ques');
        foreach ($selectedAnswers as $answer) {
            foreach ($fetchedQuestions as $question) {
                if ($question->getCorrectanswer()->getId() == $answer) {
                    $score++;
                }
            }
        }
        $totalQuestions = count($fetchedQuestions);
        $attemptedQuestions = count($selectedAnswers);
        $this->addFlash('success',"Out of ".$totalQuestions."questions you have attempted ".$attemptedQuestions." you have scored ".$score);
        return $this->redirectToRoute('exam');
    }

    /**
     * @IsGranted("ROLE_STUDENT")
     */

    #[Route('/exam',name: 'exam')]
    public function exam(EntityManagerInterface $entityManager){

        $questions = $entityManager->getRepository(Question::class)->findAll();
        return $this->render('exam/newexam.html.twig',[
            'questions' => $questions            
        ]);
    }
}