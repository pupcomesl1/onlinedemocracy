<?php

function filterBronze() {
    $badges = require('Badges.php');
    return function ($value) use ($badges) {
        return $badges[$value->type]['type'] === 'bronze';
    };
}

function filterSilver() {
    $badges = require('Badges.php');
    return function ($value) use ($badges) {
        return $badges[$value->type]['type'] === 'silver';
    };
}

function filterGold() {
    $badges = require('Badges.php');
    return function ($value) use ($badges) {
        return $badges[$value->type]['type'] === 'gold';
    };
}

function getBadges() {
    return require('Badges.php');
}
