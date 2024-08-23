<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Api;

interface AbandonedInterface
{

    /**
     * @api
     *
     * @param int $customerId
     * @return void
     */
    public function check(int $customerId): void;
}
