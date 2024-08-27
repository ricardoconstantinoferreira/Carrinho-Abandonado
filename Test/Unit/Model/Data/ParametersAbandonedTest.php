<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Model\Data;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Api\AbandonedCartRepositoryInterface;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;
use RCFerreira\AbandonedCart\Helper\HelperData;
use RCFerreira\AbandonedCart\Model\Data\AbandonedCartData;
use RCFerreira\AbandonedCart\Model\Data\QuoteAbandoned;
use RCFerreira\AbandonedCart\Model\Data\ParametersAbandoned;

class ParametersAbandonedTest extends TestCase
{

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(QuoteAbandoned&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $quoteAbandoned;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(HelperData&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $helperData;

    /**
     * @var (TimezoneInterface&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private $timezone;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(AbandonedCartData&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $abandonedCartData;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(AbandonedCartInterface&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $abandonedCart;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(AbandonedCartRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $abandonedCartRepository;

    /**
     * @var ParametersAbandoned
     */
    private $parametersAbandoned;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->quoteAbandoned = $this->getMockBuilder(QuoteAbandoned::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperData = $this->getMockBuilder(HelperData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->timezone = $this->getMockBuilder(TimezoneInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartData = $this->getMockBuilder(AbandonedCartData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCart = $this->getMockBuilder(AbandonedCartInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartRepository = $this->getMockBuilder(AbandonedCartRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->parametersAbandoned = new ParametersAbandoned(
            $this->quoteAbandoned,
            $this->helperData,
            $this->timezone,
            $this->abandonedCartData,
            $this->abandonedCart,
            $this->abandonedCartRepository
        );
    }

    /**
     * @return void
     * @throws \Zend_Db_Statement_Exception
     */
    public function testExecuteReturnsFalseWhenQuoteIsNotFound(): void
    {
        $this->quoteAbandoned->expects($this->once())
            ->method('getQuote')
            ->with($this->equalTo(1))
            ->willReturn([]);

        $result = $this->parametersAbandoned->execute(1);
        $this->assertFalse($result);
    }

    /**
     * @return void
     * @throws \Zend_Db_Statement_Exception
     */
    public function testExecuteReturnsFalseWhenNotificationIsFalse(): void
    {
        $quote = ['entity_id' => 1, 'created_at' => '2024-01-01 00:00:00'];
        $this->quoteAbandoned->expects($this->once())
            ->method('getQuote')
            ->with($this->equalTo(1))
            ->willReturn($quote);
        $this->quoteAbandoned->expects($this->once())
            ->method('isNotification')
            ->with($this->equalTo(1))
            ->willReturn(0);

        $this->helperData->expects($this->once())
            ->method('getCartHour')
            ->willReturn(1);
        $this->helperData->expects($this->once())
            ->method('getCartMinute')
            ->willReturn(0);

        $currentDate = '2024-01-01 01:00:00';
        $this->timezone->expects($this->once())
            ->method('date')
            ->willReturn(new \DateTime($currentDate));

        $result = $this->parametersAbandoned->execute(1);
        $this->assertFalse($result);
    }

    /**
     * @return void
     * @throws \Zend_Db_Statement_Exception
     */
    public function testExecuteReturnsTrueWhenConditionsAreMet(): void
    {
        $quote = ['entity_id' => 1, 'created_at' => '2024-01-01 00:00:00'];
        $this->quoteAbandoned->expects($this->once())
            ->method('getQuote')
            ->with($this->equalTo(1))
            ->willReturn($quote);
        $this->quoteAbandoned->expects($this->once())
            ->method('isNotification')
            ->with($this->equalTo(1))
            ->willReturn(1);

        $this->helperData->expects($this->once())
            ->method('getCartHour')
            ->willReturn(1);
        $this->helperData->expects($this->once())
            ->method('getCartMinute')
            ->willReturn(0);

        $currentDate = '2028-01-01 01:00:00';
        $this->timezone->expects($this->once())
            ->method('date')
            ->willReturn(new \DateTime($currentDate));

        $result = $this->parametersAbandoned->execute(1);
        $this->assertTrue($result);
    }

    /**
     * @return void
     * @throws \Zend_Db_Statement_Exception
     */
    public function testSetInvalidNotificationUpdatesNotification(): void
    {
        $quote = ['entity_id' => 1];
        $this->quoteAbandoned->expects($this->once())
            ->method('getQuote')
            ->with($this->equalTo(1))
            ->willReturn($quote);

        $data = [['entity_id' => 1]];
        $this->abandonedCartData->expects($this->once())
            ->method('getDataAbandonedCart')
            ->with($this->equalTo(1))
            ->willReturn($data);

        $this->abandonedCart->expects($this->once())
            ->method('setId')
            ->with($this->equalTo(1));
        $this->abandonedCart->expects($this->once())
            ->method('setNotification')
            ->with($this->equalTo(0));

        $this->abandonedCartRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($this->abandonedCart));

        $this->parametersAbandoned->setInvalidNotification(1);
    }
}
