<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\Data;

use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;
use RCFerreira\AbandonedCart\Helper\HelperData;

class ParametersAbandoned
{

    /**
     * @param CheckoutSessionFactory $checkoutSessionFactory
     * @param HelperData $helperData
     */
    public function __construct(
        private CheckoutSessionFactory $checkoutSessionFactory,
        private HelperData $helperData
    ) {}

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(): bool
    {
        $checkoutSession = $this->checkoutSessionFactory->create();
        $quote = $checkoutSession->getQuote();
        $created = $quote->getData('created_at');

        $hour = $this->helperData->getCartHour();
        $minute = $this->helperData->getCartMinute();

        $hourFormat = date_modify(date_create($created), "+{$hour} hour")->format("Y-m-d H:i:s");
        $hourMinuteFormat = date_modify(date_create($hourFormat), "+{$minute} minute")->format("Y-m-d H:i:s");
        $currentDate = date("Y-m-d H:i:s");

        return (strtotime($hourMinuteFormat) > strtotime($currentDate));
    }
}
