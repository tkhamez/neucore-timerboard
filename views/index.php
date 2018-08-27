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
            <th scope="col">Name</th>
            <th scope="col">Priority</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($events as $event) { ?>
            <tr>
                <th scope="row"><?= $this->esc($event->name) ?></th>
                <td></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
include '_foot.php';
