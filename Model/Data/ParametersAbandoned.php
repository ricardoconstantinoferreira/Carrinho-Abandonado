<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\Data;

use RCFerreira\AbandonedCart\Helper\HelperData;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;
use RCFerreira\AbandonedCart\Api\AbandonedCartRepositoryInterface;

class ParametersAbandoned
{

    /**
     * @param QuoteAbandoned $quoteAbandoned
     * @param HelperData $helperData
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        private QuoteAbandoned $quoteAbandoned,
        private HelperData $helperData,
        private TimezoneInterface $timezone,
        private AbandonedCartData $abandonedCartData,
        private AbandonedCartInterface $abandonedCart,
        private AbandonedCartRepositoryInterface $abandonedCartRepository
    ) {}

    /**
     * @param int $customerId
     * @return bool
     * @throws \Zend_Db_Statement_Exception
     */
    public function execute(int $customerId): bool
    {
        $quote = $this->quoteAbandoned->getQuote($customerId);

        if (!$quote) return false;

        $notification = $this->quoteAbandoned->isNotification((int) $quote['entity_id']);

        $created = $quote['created_at'];

        $hour = $this->helperData->getCartHour();
        $minute = $this->helperData->getCartMinute();

        $hourFormat = date_modify(date_create($created), "+{$hour} hour")->format("Y-m-d H:i:s");
        $hourMinuteFormat = date_modify(date_create($hourFormat), "+{$minute} minute")->format("Y-m-d H:i:s");
        $currentDate = $this->timezone->date()->format('Y-m-d H:i:s');

        return (strtotime($hourMinuteFormat) < strtotime($currentDate) && $notification);
    }

    /**
     * @param int $customerId
     * @return void
     * @throws \Zend_Db_Statement_Exception
     */
    public function setInvalidNotification(int $customerId): void
    {
        $quote = $this->quoteAbandoned->getQuote($customerId);
        $quoteId = (int) $quote['entity_id'];

        $data = $this->abandonedCartData->getDataAbandonedCart($quoteId);

        if (!empty($data)) {
            $this->abandonedCart->setId(current($data)['entity_id']);
            $this->abandonedCart->setNotification(0);

            $this->abandonedCartRepository->save($this->abandonedCart);
        }
    }
}
