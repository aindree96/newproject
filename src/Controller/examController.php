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

    #[Route('/exam/process',name: 'app_exam_process', methods:'POST')]
    public function process(Request $request,EntityManagerInterface $entityManager){
        $questions = $entityManager->getRepository(Question::class)->findAll();
        $score=0;
        $answered=$request->get('ques');
        foreach ($answered as $answer) {
            foreach ($questions as $question) {
                if ($question->getCorrectanswer()->getId() == $answer) {
                    $score++;
                }
            }
        }
        $total=count($questions);
        $questionattempt=count($answered);
        $this->addFlash('success',"Out of ".$total." you have attempted ".$questionattempt." you have scored ".$score);
        return $this->redirectToRoute('newexam');
    }

    /**
     * @IsGranted("ROLE_STUDENT")
     */

    #[Route('/exam',name: 'newexam')]
    public function exam(EntityManagerInterface $entityManager){

        $questions = $entityManager->getRepository(Question::class)->findAll();
        $answers=$entityManager->getRepository(Answer::class)->findAll();

        return $this->render('exam/newexam.html.twig',[
            'questions' => $questions,
            'answers' => $answers
        ]);
    }
}