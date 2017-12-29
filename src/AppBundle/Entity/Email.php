<?php

namespace AppBundle\Entity;
use ApiBundle\Base\Model\ResourceInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use ApiBundle\Base\RestController;
use AppBundle\Constraints as CustomAssert;

/**
 * Class Email
 * @ORM\Entity(repositoryClass="EmailRepository")
 */
class Email implements ResourceInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_BROKEN = 'broken';

    /**
     * Identifier
     *
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Type("integer")
     * @Serializer\ReadOnly()
     * @Serializer\Groups({RestController::GROUP_OUTPUT, RestController::GROUP_PRIMARY_KEY})
     */
    private $id;

    /**
     * Title
     *
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Serializer\Type("string")
     * @Serializer\Groups({RestController::GROUP_INPUT, RestController::GROUP_OUTPUT})
     */
    private $title;

    /**
     * Sender
     *
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max=100)
     * @Assert\Type("string")
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Serializer\Type("string")
     * @Serializer\Groups({RestController::GROUP_INPUT, RestController::GROUP_OUTPUT})
     */
    private $sender;

    /**
     * Recivers
     *
     * @var array
     * @ORM\Column(type="array", nullable=true)
     * @Assert\Type("array")
     * @CustomAssert\ArrayOfEmails()
     * @Assert\NotBlank()
     * @Serializer\Type("array")
     * @Serializer\Groups({RestController::GROUP_INPUT, RestController::GROUP_OUTPUT})
     */
    private $recipients;

    /**
     * Content
     *
     * @var string
     * @ORM\Column(type="text")
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Serializer\Type("string")
     * @Serializer\Groups({RestController::GROUP_INPUT, RestController::GROUP_OUTPUT})
     */
    private $content;

    /**
     * Created at
     *
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Serializer\ReadOnly()
     * @Serializer\Groups({RestController::GROUP_OUTPUT})
     */
    private $createdAt;

    /**
     * Sent at
     *
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Serializer\ReadOnly()
     * @Serializer\Groups({RestController::GROUP_OUTPUT})
     */
    private $sentAt;

    /**
     * Status
     *
     * @var string
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(max=10)
     * @Assert\Type("string")
     * @Assert\Choice(choices = {
     *     Email::STATUS_PENDING,
     *     Email::STATUS_SENT,
     *     Email::STATUS_BROKEN,
     * })
     * @Assert\NotBlank()
     * @Serializer\Type("string")
     * @Serializer\Groups({RestController::GROUP_INPUT, RestController::GROUP_OUTPUT})
     */
    private $status;

    /**
     * Priority
     *
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\Range(min=1, max=3)
     * @Assert\NotBlank()
     * @Serializer\Type("integer")
     * @Serializer\Groups({RestController::GROUP_INPUT, RestController::GROUP_OUTPUT})
     */
    private $priority;

    /**
     * Attachements
     *
     * @var array
     * @ORM\Column(type="array", nullable=true)
     * @Assert\Type("array")
     * @Serializer\Type("array")
     * @Serializer\Groups({RestController::GROUP_INPUT, RestController::GROUP_OUTPUT})
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
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param array $recipients
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
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