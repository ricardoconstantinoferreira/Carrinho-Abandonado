<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Api;

use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;

interface AbandonedCartRepositoryInterface
{

    /**
     * @param AbandonedCartInterface $abandonedCart
     * @return int
     */
    public function save(AbandonedCartInterface $abandonedCart): int;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return array
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria): array;
}
