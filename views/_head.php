<?php
/* @var \Brave\TimerBoard\View $this */
/* @var bool $isAdmin */
/* @var string $authName */
/* @var string $appName */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="initial-scale=1, shrink-to-fit=no, width=device-width" name="viewport">
    <title><?= $this->esc($appName) ?></title>
    <link rel="stylesheet" href="/vendor/easy-autocomplete.min.css">
    <link rel="stylesheet" href="/vendor/easy-autocomplete.themes.min.css">
    <link rel="stylesheet" href="/assets/bravecollective/web-ui/css/brave.css">
    <link rel="stylesheet" href="/timer-board.css">
</head>

<body class="container-fluid">
    <header class="navbar navbar-dark bg-brave shadow-1 mb-3">
        <span class="navbar-brand"><?= $this->esc($appName) ?></span>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <?php if ($isAdmin) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/new">New Event</a>
                </li>
            <?php } ?>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <?= $authName ?>
            <a class="btn btn-outline-success my-2 my-sm-0" href="/logout">Logout</a>
        </form>
    </header>
