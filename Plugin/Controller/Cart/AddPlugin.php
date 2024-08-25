<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Plugin\Controller\Cart;

use Magento\Checkout\Controller\Cart\Add;
use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;
use RCFerreira\AbandonedCart\Api\AbandonedCartRepositoryInterface;
use RCFerreira\AbandonedCart\Logger\Logger;
use RCFerreira\AbandonedCart\Model\Data\AbandonedCartData;

class AddPlugin
{
    /**
     * @param AbandonedCartInterface $abandonedCart
     * @param AbandonedCartRepositoryInterface $abandonedCartRepository
     * @param Logger $logger
     * @param CheckoutSessionFactory $checkoutSessionFactory
     * @param AbandonedCartData $abandonedCartData
     */
    public function __construct(
        private AbandonedCartInterface $abandonedCart,
        private AbandonedCartRepositoryInterface $abandonedCartRepository,
        private Logger $logger,
        private CheckoutSessionFactory $checkoutSessionFactory,
        private AbandonedCartData $abandonedCartData
    ) {}

    /**
     * @param Add $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(
        Add $subject,
        $result
    ) {
        try {
            $checkoutSession = $this->checkoutSessionFactory->create();
            $quoteId = (int) $checkoutSession->getQuote()->getId();

            if (empty($this->abandonedCartData->getDataAbandonedCart($quoteId))) {
                $this->abandonedCart->setQuote($quoteId);
                $this->abandonedCart->setNotification(1);

                $this->abandonedCartRepository->save($this->abandonedCart);
            }

        } catch (\Exception $e) {
            $this->logger->info("Notification " . $e->getMessage());
        }

        return $result;
    }
}
