<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;

class AbandonedCart extends AbstractDb
{
    public function _construct()
    {
        $this->_init(AbandonedCartInterface::TABLE, AbandonedCartInterface::ENTITY_ID);
    }
}
