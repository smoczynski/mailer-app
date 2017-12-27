<?php

namespace AppBundle\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Mail
 * @ORM\Entity(repositoryClass="MailRepository")
 */
class Mail
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max=100)
     * @Assert\NotBlank()
     */
    private $sender;

    /**
     * @var array
     * @ORM\Column(type="array")
     * @Assert\NotBlank()
     */
    private $recivers;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $sentAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(max=10)
     * @Assert\NotBlank()
     */
    private $status;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $priority;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $attachements;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return array
     */
    public function getRecivers()
    {
        return $this->recivers;
    }

    /**
     * @param array $recivers
     */
    public function setRecivers($recivers)
    {
        $this->recivers = $recivers;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * @param DateTime $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return array
     */
    public function getAttachements()
    {
        return $this->attachements;
    }

    /**
     * @param array $attachements
     */
    public function setAttachements($attachements)
    {
        $this->attachements = $attachements;
    }


}