<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\Sales;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderAbandoned
{

    public function __construct(
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function quoteIsOrder(int $quoteId): bool
    {

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter("quote_id", $quoteId)
            ->create();
        $items = $this->orderRepository->getList($searchCriteria)->getItems();

        return false;
    }
}
