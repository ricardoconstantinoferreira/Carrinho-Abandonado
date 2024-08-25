<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\Data;

use Magento\Quote\Model\ResourceModel\Quote\Collection;

class QuoteAbandoned
{

    /**
     * @param Collection $collection
     * @param AbandonedCartData $abandonedCartData
     */
    public function __construct(
        private Collection $collection,
        private AbandonedCartData $abandonedCartData
    ) {}

    /**
     * @param int $customerId
     * @return array|bool
     * @throws \Zend_Db_Statement_Exception
     */
    public function getQuote(int $customerId): array|bool
    {

        $collection = $this->collection->getSelect()
            ->where("main_table.entity_id not in (select quote_id from sales_order)")
            ->where("main_table.customer_id = {$customerId}");

        return $collection->query()->fetch();
    }

    public function getCustomers(): array
    {
        $collection = $this->collection->getSelect()
            ->where("main_table.customer_id in (select entity_id from customer_entity)")
            ->group("customer_id");

        return $collection->query()->fetchAll();
    }

    /**
     * @param int $quoteId
     * @return bool
     */
    public function isNotification(int $quoteId): int
    {
        $data = $this->abandonedCartData->getDataAbandonedCart($quoteId);

        if (!empty($data)) {
            return (int) current($data)['notification'];
        }

        return 0;
    }
}
