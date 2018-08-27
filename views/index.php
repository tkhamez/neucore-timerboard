<?php
/* @var $this \Brave\TimerBoard\View */
/* @var $events \Brave\TimerBoard\Entity\Event[] */
/* @var $isAdmin bool */

include '_head.php'; // needs $isAdmin variable
?>

<h1 class="text-light">Timers</h1>

<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">Location</th>
            <th scope="col">Priority</th>
            <th scope="col">Structure</th>
            <th scope="col">Type</th>
            <th scope="col">EVE Time</th>
            <th scope="col">Win/Loss</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($events as $event) { ?>
            <tr>
                <th scope="row"><?= $this->esc($event->location) ?></th>
                <td><?= $this->esc($event->priority) ?></td>
                <td><?= $this->esc($event->structure) ?></td>
                <td><?= $this->esc($event->type) ?></td>
                <td>
                    <?php if ($event->eventTime) { ?>
                        <a href="http://time.nakamura-labs.com/#<?= $event->eventTime->getTimestamp() ?>"
                                target="_blank">
                            <?= $event->eventTime->format('Y-m-d H:i:s') ?>
                        </a>
                    <?php } ?>
                </td>
                <td><?= $this->esc($event->result) ?></td>
                <td>
                    <?php if ($isAdmin) { ?>
                        <a href="/admin/<?= (int) $event->getId() ?>">edit</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
include '_foot.php';
