<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Api\Data;

interface AbandonedCartInterface
{

    public const TABLE = "rcferreira_abandoned_cart";

    public const ENTITY_ID = "entity_id";

    public const QUOTE_ID = "quote_id";

    public const NOTIFICATION = "notification";

    /**
     * @param $entity_id
     * @return mixed
     */
    public function setId($entity_id);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param int $quoteId
     * @return AbandonedCartInterface
     */
    public function setQuote(int $quoteId): AbandonedCartInterface;

    /**
     * @return int
     */
    public function getQuote(): int;

    /**
     * @param int $notification
     * @return AbandonedCartInterface
     */
    public function setNotification(int $notification): AbandonedCartInterface;

    /**
     * @return int
     */
    public function getNotification(): int;
}
