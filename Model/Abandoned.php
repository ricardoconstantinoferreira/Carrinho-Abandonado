<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model;

use RCFerreira\AbandonedCart\Api\AbandonedInterface;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use RCFerreira\AbandonedCart\Model\Data\ParametersAbandoned;

class Abandoned implements AbandonedInterface
{

    private $customerSession;

    /**
     * @param CustomerSessionFactory $customerSessionFactory
     * @param ParametersAbandoned $parametersAbandoned
     */
    public function __construct(
        private CustomerSessionFactory $customerSessionFactory,
        private ParametersAbandoned $parametersAbandoned
    ) {
        $this->customerSession = $this->customerSessionFactory->create();
    }

    public function check(): bool
    {
        if ($this->customerSession->isLoggedIn()) {
            if ($this->parametersAbandoned->execute()) {
                //send e-mail
            }
        }

        return false;
    }
}
