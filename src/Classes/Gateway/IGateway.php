<?php

namespace App\Classes\Gateway;

interface IGateway
{
    public function process(): bool;
}