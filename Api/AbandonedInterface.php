<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Api;

interface AbandonedInterface
{

    /**
     * @api
     *
     * @return bool
     */
    public function check(): bool;
}
