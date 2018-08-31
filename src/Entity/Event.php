<?php
namespace Brave\TimerBoard\Entity;

/**
 * @Entity
 * @Table(name="events")
 */
class Event
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="System", inversedBy="events")
     * @JoinColumn(name="system", referencedColumnName="name")
     * @var System
     */
    private $system;

    /**
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    public $priority;

    /**
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    public $structure;

    /**
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    public $type;

    /**
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    public $standing;

    /**
     * @Column(type="datetime", name="event_time", nullable=true)
     * @var \DateTime
     */
    public $eventTime;

    /**
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    public $result; // win/loss

    /**
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    public $notes;

    public function getId()
    {
        return $this->id;
    }

    public function setSystem(System $system = null): self
    {
        $this->system = $system;

        return $this;
    }

    /**
     * @return System|null
     */
    public function getSystem()
    {
        return $this->system;
    }
}
