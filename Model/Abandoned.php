<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use RCFerreira\AbandonedCart\Api\AbandonedInterface;
use RCFerreira\AbandonedCart\Model\Data\ParametersAbandoned;
use RCFerreira\AbandonedCart\Model\Email\Send;

class Abandoned implements AbandonedInterface
{
    /**
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param ParametersAbandoned $parametersAbandoned
     * @param Send $send
     */
    public function __construct(
        private CustomerRepositoryInterface $customerRepositoryInterface,
        private ParametersAbandoned $parametersAbandoned,
        private Send $send
    ) {}

    /**
     * @param int $customerId
     * @return void
     * @throws LocalizedException
     */
    public function check(int $customerId): void
    {
        try {
            $customer = $this->customerRepositoryInterface->getById($customerId);

            if ($this->parametersAbandoned->execute($customerId)) {
                $this->send->sendMail($customer);
                $this->parametersAbandoned->setInvalidNotification($customerId);
            }
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }
    }
}
