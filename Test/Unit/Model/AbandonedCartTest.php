<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Model\AbandonedCart;
use Magento\Framework\Model\AbstractModel;

class AbandonedCartTest extends TestCase
{
    private AbandonedCart $abandonedCart;

    protected function setUp(): void
    {
        $this->abandonedCart = $this->getMockBuilder(AbandonedCart::class)
            ->setMethods(['_construct']) // Mock only the methods we need
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testSetAndGetId(): void
    {
        $entityId = 123;
        $this->abandonedCart->setId($entityId);
        $this->assertSame($entityId, $this->abandonedCart->getId());
    }

    public function testSetAndGetQuote(): void
    {
        $quoteId = 456;
        $this->abandonedCart->setQuote($quoteId);
        $this->assertSame($quoteId, $this->abandonedCart->getQuote());
    }

    public function testSetAndGetNotification(): void
    {
        $notification = 1;
        $this->abandonedCart->setNotification($notification);
        $this->assertSame($notification, $this->abandonedCart->getNotification());
    }
}
