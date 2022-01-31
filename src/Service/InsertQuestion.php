<?php
namespace App\Service;


use App\Entity\Question;
use App\Entity\Answer;

class InsertQuestion
{
    public function setQuestion($question,$arrayOfFetchedAnswers, $entityManager)
    {
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
    }
}