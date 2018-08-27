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
     * @Column(type="string", length=255)
     * @var string
     */
    public $name;

    public function getId()
    {
        return $this->id;
    }
}
