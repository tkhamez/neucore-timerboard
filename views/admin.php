<?php
/* @var $this \Brave\TimerBoard\View */
/* @var $isAdmin bool */
/* @var $authName string */
/* @var $systemNames string[] */
/* @var $event \Brave\TimerBoard\Entity\Event */

include '_head.php'; // needs $isAdmin and $authName variables
?>

<h1 class="text-light">
    <?= $event->getId() ? 'Edit Event' : 'New event' ?>
</h1>

<div class="col mb-3 pt-3 pb-3 bg-dark text-light">
    Last edited
    by <?= $event->updatedBy ? $event->updatedBy : 'unknown' ?>,
    at <?= $event->updatedAt ? $event->updatedAt->format('Y-m-d H:i:s') : 'unknown' ?> UTC
</div>

<form class="col mb-3 pt-3 pb-3 bg-dark text-light" action="/admin/<?= (int) $event->getId() ?>" method="post">
    <div class="form-group">
        <label class="text-light" for="system">System</label>
        <input type="text" class="form-control bg-light-1 text-dark" id="system" name="system" maxlength="255"
               required value="<?= $event->getSystem() ? $this->esc($event->getSystem()->name) : '' ?>"
               data-systems="<?= $this->esc(json_encode($systemNames)) ?>">
        <small class="form-text text-white-50">Enter the system name, e. g. GE-8JV</small>
    </div>
    <div class="form-group">
        <label class="text-light" for="priority">Priority</label>
        <select class="form-control bg-light-1" id="priority" name="priority" required>
            <option value="">please select</option>
            <option <?= $event->priority === 'Low' ? 'selected' : '' ?>>Low</option>
            <option <?= $event->priority === 'Medium' ? 'selected' : '' ?>>Medium</option>
            <option <?= $event->priority === 'High' ? 'selected' : '' ?>>High</option>
            <option <?= $event->priority === 'Critical' ? 'selected' : '' ?>>Critical</option>
        </select>
    </div>
    <div class="form-group">
        <label class="text-light" for="structure">Structure</label>
        <select class="form-control bg-light-1" id="structure" name="structure" required>
            <option value="">please select</option>
            <option <?= $event->structure === 'TCU' ? 'selected' : '' ?>>TCU</option>
            <option <?= $event->structure === 'IHub' ? 'selected' : '' ?>>IHub</option>
            <option <?= $event->structure === 'POS' ? 'selected' : '' ?>>POS</option>
            <option <?= $event->structure === 'POCO' ? 'selected' : '' ?>>POCO</option>
            <option <?= $event->structure === 'Raitaru' ? 'selected' : '' ?>>Raitaru</option>
            <option <?= $event->structure === 'Azbel' ? 'selected' : '' ?>>Azbel</option>
            <option <?= $event->structure === 'Sotiyo' ? 'selected' : '' ?>>Sotiyo</option>
            <option <?= $event->structure === 'Athanor' ? 'selected' : '' ?>>Athanor</option>
            <option <?= $event->structure === 'Tatara' ? 'selected' : '' ?>>Tatara</option>
            <option <?= $event->structure === 'Astrahus' ? 'selected' : '' ?>>Astrahus</option>
            <option <?= $event->structure === 'Fortizar' ? 'selected' : '' ?>>Fortizar</option>
            <option <?= $event->structure === 'Keepstar' ? 'selected' : '' ?>>Keepstar</option>
            <option <?= $event->structure === 'Pharolynx' ? 'selected' : '' ?> value="Pharolynx">
                Pharolynx Cyno Beacon</option>
            <option <?= $event->structure === 'Tenebrex' ? 'selected' : '' ?> value="Tenebrex">
                Tenebrex Cyno Jammer</option>
            <option <?= $event->structure === 'Ansiblex' ? 'selected' : '' ?> value="Ansiblex">
                Ansiblex Jump Gate</option>
            <option <?= $event->structure === 'other' ? 'selected' : '' ?>>other</option>
        </select>
    </div>
    <div class="form-group">
        <label class="text-light" for="type">Type</label>
        <select class="form-control bg-light-1" id="type" name="type" required>
            <option value="">please select</option>
            <option <?= $event->type === 'Anchoring' ? 'selected' : '' ?>>Anchoring</option>
            <option <?= $event->type === 'Shield' ? 'selected' : '' ?>>Shield</option>
            <option <?= $event->type === 'Armor' ? 'selected' : '' ?>>Armor</option>
            <option <?= $event->type === 'Structure' ? 'selected' : '' ?>>Structure</option>
            <option <?= $event->type === 'other' ? 'selected' : '' ?>>other</option>
        </select>
    </div>
    <div class="form-group">
        <label class="text-light" for="standing">Standings</label>
        <select class="form-control bg-light-1" id="standing" name="standing" required>
            <option value="">please select</option>
            <option <?= $event->standing === 'friendly' ? 'selected' : '' ?>>friendly</option>
            <option <?= $event->standing === 'enemy' ? 'selected' : '' ?>>enemy</option>
            <option <?= $event->standing === 'neutral' ? 'selected' : '' ?>>neutral</option>
            <option <?= $event->standing === 'other' ? 'selected' : '' ?>>other</option>
        </select>
    </div>
    <div class="form-row">
        <div class="col form-group">
            <label class="text-light" for="eventDate">EVE Date and time</label>
            <input type="date" class="form-control bg-light-1" id="eventDate" name="date"
                   maxlength="10" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                   value="<?= $event->eventTime ? $event->eventTime->format('Y-m-d') : '' ?>">
            <small class="form-text text-white-50 mt-0 mb-2">
                Enter the date (format yyyy-mm-dd if browser does not support a date picker)
            </small>
            <input type="text" class="form-control bg-light-1" id="eventTime" name="time"
                   maxlength="5" pattern="[0-9]{1,2}:[0-9]{2}"
                   value="<?= $event->eventTime ? $this->esc($event->eventTime->format('H:i')) : '' ?>">
            <label class="form-text text-white-50 mt-0" for="eventTime">Enter the time, format e.g.: 16:05</label>
        </div>
        <div class="col form-group">
            <small class="text-light">Or enter a relative time (has priority over date)</small>

            <input type="text" class="form-control bg-light-1" id="days" name="days" pattern="[0-9]*" value="">
            <label class="form-text text-white-50 mt-0 mb-2" for="days">days</label>

            <input type="text" class="form-control bg-light-1" name="hours" id="hours" pattern="[0-9]*" value="">
            <label class="form-text text-white-50 mt-0 mb-2" for="hours">hours</label>

            <input type="text" class="form-control bg-light-1" name="minutes" id="minutes" pattern="[0-9]*" value="">
            <label class="form-text text-white-50 mt-0" for="minutes">minutes</label>
        </div>
    </div>
    <div class="form-group">
        <label class="text-light" for="result">Result</label>
        <select class="form-control bg-light-1" id="result" name="result">
            <option <?= $event->result === 'No data' ? 'selected' : '' ?>>No data</option>
            <option <?= $event->result === 'Win' ? 'selected' : '' ?>>Win</option>
            <option <?= $event->result === 'Loss' ? 'selected' : '' ?>>Loss</option>
        </select>
    </div>
    <div class="form-group">
        <label class="text-light" for="notes">Notes</label>
        <input type="text" class="form-control bg-light-1" id="notes" name="notes" maxlength="255"
               value="<?= $this->esc($event->notes) ?>">
        <small class="form-text text-white-50">Optional notes</small>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php if ($event->getId()) { ?>
    <div class="col mb-3 pt-3 pb-3 bg-dark text-light">
        <form action="/admin/delete/<?= (int) $event->getId() ?>" method="post" name="delete-event"></form>
        <button type="submit" class="btn btn-danger" data-container="body" data-toggle="popover" data-placement="right"
                data-trigger="focus" data-html="true" title="Are you sure?"
                data-content='
                    <span class="btn btn-xs btn-primary delete-event">Yes</span>
                    <span class="btn btn-xs btn-default">no</span>'
        >
            delete
        </button>
    </div>
<?php } ?>

<?php
include '_foot.php';
