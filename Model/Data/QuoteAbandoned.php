<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\Data;

use Magento\Quote\Model\ResourceModel\Quote\Collection;

class QuoteAbandoned
{

    /**
     * @param Collection $collection
     */
    public function __construct(
        private Collection $collection
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
}
