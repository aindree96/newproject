<?php

namespace App\Controller;



use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class examController extends AbstractController
{
    /**
     * @Route("/",name="homepage")
     */
    public function homepage(){
        return $this->render('front/homepage.html.twig');
    }


    /**
     * @Route("exam/process",name="app_exam_process",methods="POST")
     */

    public function process(Request $request,EntityManagerInterface $entityManager){
        $question = $entityManager->getRepository(Question::class)->findAll();
        $score=0;

        $answered=$request->get('ques');

        foreach ($answered as $answer) {
            foreach ($question as $questions) {
                if ($questions->getAnswer() === $answer) {
                    $score++;
                }
            }
        }
        $total=count($question);
        $questionattempt=count($answered);
        $this->addFlash('success',"Out of ".$total." you have attempted ".$questionattempt." you have scored ".$score);
        return $this->redirectToRoute('newexam');
    }

    /**
     * @Route("/exam" , name="newexam")
     * @IsGranted("ROLE_STUDENT")
     */

    public function exam(EntityManagerInterface $entityManager){

        $repository = $entityManager->getRepository(Question::class);
        $question = $repository->findAll();

        return $this->render('exam/newexam.html.twig',[
            'question' => $question,
        ]);
    }
}