<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Cron;

use RCFerreira\AbandonedCart\Model\Data\QuoteAbandoned;
use RCFerreira\AbandonedCart\Model\Abandoned as ModelAbandoned;

class Abandoned
{
    /**
     * @param QuoteAbandoned $quoteAbandoned
     * @param ModelAbandoned $modelAbandoned
     */
    public function __construct(
        private QuoteAbandoned $quoteAbandoned,
        private ModelAbandoned $modelAbandoned
    ) {}

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): void
    {
        $customers = $this->quoteAbandoned->getCustomers();

        if (!empty($customers)) {
            foreach ($customers as $customer) {
                $this->modelAbandoned->check((int) $customer['customer_id']);
            }
        }
    }
}
