<?php
namespace Brave\TimerBoard\Entity;

/**
 * @Entity
 * @Table(name="systems")
 */
class System
{
    /**
     * @OneToMany(targetEntity="Event", mappedBy="system")
     * @var \Doctrine\Common\Collections\Collection
     */
    private $events;

    /**
     * @Id
     * @NONE
     * @Column(type="string", length=255)
     * @var string
     */
    public $name;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    public $constellation;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    public $region;

    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addEvent(Event $event): self
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * @param Event $event
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEvent(Event $event): bool
    {
        return $this->events->removeElement($event);
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events->toArray();
    }
}
