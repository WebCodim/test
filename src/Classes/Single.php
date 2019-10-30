<?php

namespace App\Classes;

abstract class Single
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            return new static();
        }

        return static::$instance;
    }

    protected function __construct()
    {

    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }
}