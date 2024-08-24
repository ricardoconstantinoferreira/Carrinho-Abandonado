<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\Data;

use Magento\Framework\Api\SearchCriteriaBuilder;
use RCFerreira\AbandonedCart\Api\AbandonedCartRepositoryInterface;

class AbandonedCartData
{
    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AbandonedCartRepositoryInterface $abandonedCartRepository
     */
    public function __construct(
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private AbandonedCartRepositoryInterface $abandonedCartRepository
    ) {}

    /**
     * @param int $quoteId
     * @return array
     */
    public function getDataAbandonedCart(int $quoteId): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('quote_id', $quoteId)
            ->create();
        return $this->abandonedCartRepository->getList($searchCriteria);
    }
}
