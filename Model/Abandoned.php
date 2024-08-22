<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model;

use RCFerreira\AbandonedCart\Api\AbandonedInterface;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;
use RCFerreira\AbandonedCart\Model\Sales\OrderAbandoned;

class Abandoned implements AbandonedInterface
{

    private $customerSession;

    private $checkoutSession;

    public function __construct(
        private CustomerSessionFactory $customerSessionFactory,
        private CheckoutSessionFactory $checkoutSessionFactory,
        private OrderAbandoned $orderAbandoned
    ) {
        $this->customerSession = $this->customerSessionFactory->create();
        $this->checkoutSession = $this->checkoutSessionFactory->create();
    }

    public function check(): bool
    {
        if ($this->customerSession->isLoggedIn()) {
            $quoteId = (int) $this->checkoutSession->getQuote()->getId();
            $this->orderAbandoned->quoteIsOrder($quoteId);
        }

        return false;
    }

}
