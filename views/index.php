<?php
/* @var $this \Brave\TimerBoard\View */
/* @var $isAdmin bool */
/* @var $authName string */
/* @var $activeEvents \Brave\TimerBoard\Entity\Event[] */
/* @var $expiredEvents \Brave\TimerBoard\Entity\Event[] */
/* @var $currentPage int */
/* @var $pages int */

include '_head.php'; // needs $isAdmin and $authName variables
?>


<?php
foreach ([$activeEvents, $expiredEvents] as $idx => $events) {
    /* @var $events \Brave\TimerBoard\Entity\Event[] */
?>
    <h3 class="text-light">
        <?= $idx === 0 ? 'Active' : 'Expired' ?>
        Timers
    </h3>
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">System</th>
                <!-- <th scope="col">Constellation</th> -->
                <th scope="col">Region</th>
                <th scope="col">Priority</th>
                <th scope="col">Structure</th>
                <th scope="col">Type</th>
                <th scope="col">Standing</th>
                <th scope="col">Relative Time</th>
                <th scope="col">Local Time</th>
                <th scope="col">EVE Time</th>
                <th scope="col">Result</th>
                <?php if ($isAdmin) { ?>
                    <th scope="col">Action</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($events as $event) {
                $system = $event->getSystem() ? $event->getSystem()->name : '';
                $systemLink = str_replace(' ', '_', $system);
                $region = $event->getSystem() ? $event->getSystem()->region : '';
                $regionLink = str_replace(' ', '_', $region);
                $time = $event->eventTime ? $event->eventTime->getTimestamp() : '';
                $timeFormat = $event->eventTime ? $event->eventTime->format('Y-m-d H:i') : '';
            ?>
                <tr>
                    <th scope="row">
                        <a href="http://evemaps.dotlan.net/system/<?= $systemLink ?>" target="_blank">
                            <?= $system ?>
                        </a>
                    </th>
                    <!-- <td><?= $event->getSystem() ? $event->getSystem()->constellation : '' ?></td> -->
                    <td>
                        <a href="http://evemaps.dotlan.net/map/<?= $regionLink.'/'.$systemLink ?>" target="_blank">
                            <?= $region ?>
                        </a>
                    </td>
                    <td><?= $this->esc($event->priority) ?></td>
                    <td><?= $this->esc($event->structure) ?></td>
                    <td><?= $this->esc($event->type) ?></td>
                    <td><?= $this->esc($event->standing) ?></td>
                    <td class="time-relative" data-time="<?= $time ?>"></td>
                    <td class="time-local" data-time="<?= $time ?>"></td>
                    <td>
                        <a href="http://time.nakamura-labs.com/#<?= $time ?>" target="_blank">
                            <?= $timeFormat ?>
                        </a>
                    </td>
                    <td><?= $this->esc($event->result) ?></td>
                    <?php if ($isAdmin) { ?>
                        <td>
                            <a href="/admin/<?= (int) $event->getId() ?>">edit</a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<nav>
    <ul class="pagination bg-dark">
        <li class="page-item">
            <a class="page-link text-light" href="/?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <?php
        $spacesBeforeShown = false;
        $spacesAfterShown = false;
        foreach (range(1, $pages) as $page) {
            if (abs($page - $currentPage) > 4 && $page > 1 && $page < $pages) {
                if ($page < $currentPage) {
                    if ($spacesBeforeShown) {
                        continue;
                    }
                    $spacesBeforeShown = true;
                }
                if ($page > $currentPage) {
                    if ($spacesAfterShown) {
                        continue;
                    }
                    $spacesAfterShown = true;
                }
        ?>
                <li class="page-item disabled">
                    <a class="page-link text-light" href="#" tabindex="-1">...</a>
                </li>
        <?php
            } else {
        ?>
                <li class="page-item <?= $page == $currentPage ? 'active' : '' ?>">
                    <a class="page-link text-light" href="/?page=<?= $page ?>"><?= $page ?></a>
                </li>
        <?php
            }
        }
        ?>
        <li class="page-item">
            <a class="page-link text-light" href="/?page=<?= $currentPage + 1 ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>

<?php
include '_foot.php';
