<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class HelperData extends AbstractHelper
{

    public const CART_HOUR = "ferreira_abandonedcart/general/abandoned_cart_hour";

    public const CART_MINUTE = "ferreira_abandonedcart/general/abandoned_cart_minute";

    public const CART_NAME = "ferreira_abandonedcart/general/abandoned_cart_name";

    public const CART_EMAIL = "ferreira_abandonedcart/general/abandoned_cart_email";

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getCartHour(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CART_HOUR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getCartMinute(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CART_MINUTE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCartName(): string
    {
        return $this->scopeConfig->getValue(
            self::CART_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCartEmail(): string
    {
        return $this->scopeConfig->getValue(
            self::CART_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }
}

