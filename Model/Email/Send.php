<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model\Email;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Url;
use RCFerreira\AbandonedCart\Helper\HelperData;
use RCFerreira\AbandonedCart\Logger\Logger;

class Send
{

    /**
     * @param StateInterface $stateInterface
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param Url $url
     * @param HelperData $helperData
     * @param StoreManagerInterface $storeManager
     * @param Logger $logger
     */
    public function __construct(
        private StateInterface $stateInterface,
        private Escaper $escaper,
        private TransportBuilder $transportBuilder,
        private Url $url,
        private HelperData $helperData,
        private StoreManagerInterface $storeManager,
        private Logger $logger
    ) {}

    /**
     * @param CustomerInterface $customer
     * @return void
     */
    public function sendMail(CustomerInterface $customer): void
    {
        try {
            $this->stateInterface->suspend();

            $sender_name = $this->helperData->getCartName();
            $sender_email = $this->helperData->getCartEmail();
            $sender = [
                'name' => $this->escaper->escapeHtml($sender_name),
                'email' => $this->escaper->escapeHtml($sender_email),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('abandoned_cart_label_template')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId(),
                    ]
                )
                ->setTemplateVars([
                    'customer_name'  => $customer->getFirstname() . " " . $customer->getLastname(),
                    'customer_login' => $this->url->getLoginUrl()
                ])
                ->setFrom($sender)
                ->addTo($customer->getEmail())
                ->getTransport();
            $transport->sendMessage();
            $this->stateInterface->resume();
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }
}
