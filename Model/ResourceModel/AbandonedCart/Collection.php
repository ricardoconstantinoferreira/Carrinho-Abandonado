<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use RCFerreira\AbandonedCart\Model\AbandonedCart;
use RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart as AbandonedCartResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(AbandonedCart::class, AbandonedCartResourceModel::class);
    }
}
