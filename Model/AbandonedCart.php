<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model;

use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;
use Magento\Framework\Model\AbstractModel;

class AbandonedCart extends AbstractModel implements AbandonedCartInterface
{

    public function _construct()
    {
        $this->_init(\RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart::class);
    }

    /**
     * @param $entity_id
     * @return mixed|AbandonedCart
     */
    public function setId($entity_id)
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
    }

    /**
     * @return array|mixed|null
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param int $quoteId
     * @return AbandonedCartInterface
     */
    public function setQuote(int $quoteId): AbandonedCartInterface
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * @return int
     */
    public function getQuote(): int
    {
        return (int) $this->getData(self::QUOTE_ID);
    }

    /**
     * @param int $notification
     * @return AbandonedCartInterface
     */
    public function setNotification(int $notification): AbandonedCartInterface
    {
        return $this->setData(self::NOTIFICATION, $notification);
    }

    /**
     * @return int
     */
    public function getNotification(): int
    {
        return (int) $this->getData(self::NOTIFICATION);
    }
}
