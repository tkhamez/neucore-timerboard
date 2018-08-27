<?php
namespace Brave\TimerBoard\Repository;

use Brave\TimerBoard\Entity\Event;
use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    /**
     * @return Event[]
     */
    public function findActiveTimers()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e 
                FROM Brave\TimerBoard\Entity\Event e 
                WHERE e.eventTime >= CURRENT_TIMESTAMP()
                ORDER BY e.eventTime ASC'
            )
            ->getResult();
    }

    /**
     * @param int $from
     * @param int $limit
     * @return Event[]
     */
    public function findExpiredTimers($from = 0, $limit = 20)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e 
                FROM Brave\TimerBoard\Entity\Event e 
                WHERE e.eventTime < CURRENT_TIMESTAMP() OR e.eventTime IS NULL
                ORDER BY e.eventTime DESC'
            )
            ->setMaxResults((int) $limit)
            ->setFirstResult((int) $from)
            ->getResult();
    }

    /**
     * @return int
     */
    public function numberOfExpiredTimers()
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(e) AS num
                FROM Brave\TimerBoard\Entity\Event e 
                WHERE e.eventTime < CURRENT_TIMESTAMP()'
            )
            ->getResult();

        return isset($result[0]['num']) ? (int) $result[0]['num'] : 0;
    }
}
