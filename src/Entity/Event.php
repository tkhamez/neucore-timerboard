<?php

namespace Brave\TimerBoard\Entity;

/**
 * @Entity(repositoryClass="Brave\TimerBoard\Repository\EventRepository")
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
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    public $location;

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
     * @Column(type="datetime", name="event_time", nullable=true)
     * @var \DateTime
     */
    public $eventTime;

    /**
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    public $result; // win/loss

    public function getId()
    {
        return $this->id;
    }
}
