<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $qid;

    #[ORM\Column(type: 'integer')]
    private $subjectid;

    #[ORM\Column(type: 'string', length: 255)]
    private $question;

    #[ORM\Column(type: 'string', length: 255)]
    private $wrong1;

    #[ORM\Column(type: 'string', length: 255)]
    private $wrong2;

    #[ORM\Column(type: 'string', length: 255)]
    private $wrong3;

    #[ORM\Column(type: 'string', length: 255)]
    private $wrong4;

    #[ORM\Column(type: 'string', length: 255)]
    private $answer;

    #[ORM\Column(type: 'datetime')]
    private $datetime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQid(): ?string
    {
        return $this->qid;
    }

    public function setQid(string $qid): self
    {
        $this->qid = $qid;

        return $this;
    }

    public function getSubjectid(): ?int
    {
        return $this->subjectid;
    }

    public function setSubjectid(int $subjectid): self
    {
        $this->subjectid = $subjectid;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getWrong1(): ?string
    {
        return $this->wrong1;
    }

    public function setWrong1(string $wrong1): self
    {
        $this->wrong1 = $wrong1;

        return $this;
    }

    public function getWrong2(): ?string
    {
        return $this->wrong2;
    }

    public function setWrong2(string $wrong2): self
    {
        $this->wrong2 = $wrong2;

        return $this;
    }

    public function getWrong3(): ?string
    {
        return $this->wrong3;
    }

    public function setWrong3(string $wrong3): self
    {
        $this->wrong3 = $wrong3;

        return $this;
    }

    public function getWrong4(): ?string
    {
        return $this->wrong4;
    }

    public function setWrong4(string $wrong4): self
    {
        $this->wrong4 = $wrong4;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }
}
