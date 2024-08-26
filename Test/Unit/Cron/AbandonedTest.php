<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Cron;

use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Cron\Abandoned;
use RCFerreira\AbandonedCart\Model\Data\QuoteAbandoned;
use RCFerreira\AbandonedCart\Model\Abandoned as ModelAbandoned;

class AbandonedTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(QuoteAbandoned&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $quoteAbandonedMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(ModelAbandoned&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $modelAbandonedMock;

    /**
     * @var Abandoned
     */
    private $abandoned;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->quoteAbandonedMock = $this->getMockBuilder(QuoteAbandoned::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->modelAbandonedMock = $this->getMockBuilder(ModelAbandoned::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandoned = new Abandoned(
            $this->quoteAbandonedMock,
            $this->modelAbandonedMock
        );
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testExecuteWithCustomers()
    {
        $customers = [
            ['customer_id' => 1],
            ['customer_id' => 2]
        ];
        $this->quoteAbandonedMock
            ->method('getCustomers')
            ->willReturn($customers);

        $this->modelAbandonedMock
            ->expects($this->exactly(2))
            ->method('check')
            ->withConsecutive([1], [2]);

        $this->abandoned->execute();
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testExecuteWithNoCustomers()
    {
        $this->quoteAbandonedMock
            ->method('getCustomers')
            ->willReturn([]);

        $this->modelAbandonedMock
            ->expects($this->never())
            ->method('check');

        $this->abandoned->execute();
    }
}
