<?php
/* @var $this \Brave\TimerBoard\View */
/* @var $isAdmin bool */
/* @var $authName string */
/* @var $event \Brave\TimerBoard\Entity\Event */

include '_head.php'; // needs $isAdmin and $authName variables
?>

<h1 class="text-light">Edit Event</h1>

<form class="col mb-3 pt-3 pb-3 bg-dark text-light" action="/admin/<?= (int) $event->getId() ?>" method="post">
    <div class="form-group">
        <label class="text-light" for="location">Location</label>
        <input type="text" class="form-control" id="location" name="location"
               required
               value="<?= $this->esc($event->location) ?>">
        <small class="form-text text-white-50">Enter the system name, e. g. GE-8JV</small>
    </div>
    <div class="form-group">
        <label class="text-light" for="priority">Priority</label>
        <input type="text" class="form-control" id="priority" name="priority"
               value="<?= $this->esc($event->priority) ?>">
        <small class="form-text text-white-50">Enter the priority, e. g. Important</small>
    </div>
    <div class="form-group">
        <label class="text-light" for="structure">Structure</label>
        <input type="text" class="form-control" id="structure" name="structure"
               value="<?= $this->esc($event->structure) ?>">
        <small class="form-text text-white-50">Enter the structure, e. g. Citadel [M]</small>
    </div>
    <div class="form-group">
        <label class="text-light" for="type">Type</label>
        <input type="text" class="form-control" id="type" name="type"
               value="<?= $this->esc($event->type) ?>">
        <small class="form-text text-white-50">Enter the type, e. g. Armor</small>
    </div>
    <div class="form-row">
        <div class="col">
            <div class="form-group">
                <label class="text-light" for="eventTime">EVE Date</label>
                <input type="date" class="form-control" id="eventTime" name="date"
                       required
                value="<?= $event->eventTime ? $event->eventTime->format('Y-m-d') : '' ?>">
                <small class="form-text text-white-50">Enter the date</small>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label class="text-light" for="eventTime">EVE Time</label>
                <input type="text" class="form-control" id="eventTime" name="time"
                       maxlength="5" pattern="[0-9]{2}:[0-9]{2}" required
                       value="<?= $event->eventTime ? $event->eventTime->format('H:i') : '' ?>">
                <small class="form-text text-white-50">Enter the time, format e.g.: 02:15</small>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="text-light" for="result">Win/Loss</label>
        <input type="text" class="form-control" id="result" name="result"
               value="<?= $this->esc($event->result) ?>">
        <small class="form-text text-white-50">Enter the result, e. g. win</small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php
include '_foot.php';
