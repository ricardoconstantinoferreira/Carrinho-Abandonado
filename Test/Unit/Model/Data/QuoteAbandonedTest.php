<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Model\Data;

use Magento\Quote\Model\ResourceModel\Quote\Collection;
use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Model\Data\AbandonedCartData;
use RCFerreira\AbandonedCart\Model\Data\QuoteAbandoned;
use Zend_Db_Statement_Exception;

class QuoteAbandonedTest extends TestCase
{
    /**
     * @var (Collection&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private $collection;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(AbandonedCartData&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $abandonedCartData;

    /**
     * @var QuoteAbandoned
     */
    private $quoteAbandoned;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartData = $this->getMockBuilder(AbandonedCartData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteAbandoned = new QuoteAbandoned(
            $this->collection,
            $this->abandonedCartData
        );
    }

    /**
     * @return void
     * @throws Zend_Db_Statement_Exception
     */
    public function testGetQuoteReturnsArray(): void
    {
        $customerId = 1;
        $expectedResult = ['entity_id' => 1, 'customer_id' => $customerId];

        $queryMock = $this->createMock(\Zend_Db_Statement_Interface::class);
        $queryMock->method('fetch')
            ->willReturn($expectedResult);

        $selectMock = $this->createMock(\Magento\Framework\DB\Select::class);
        $selectMock->method('where')
            ->willReturnSelf();
        $selectMock->method('query')
            ->willReturn($queryMock);

        $this->collection->method('getSelect')
            ->willReturn($selectMock);

        $result = $this->quoteAbandoned->getQuote($customerId);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return void
     * @throws Zend_Db_Statement_Exception
     */
    public function testGetQuoteReturnsFalseOnException(): void
    {
        $this->expectException(Zend_Db_Statement_Exception::class);

        $customerId = 1;
        $this->collection->method('getSelect')
            ->willThrowException(new Zend_Db_Statement_Exception());

        $this->quoteAbandoned->getQuote($customerId);
    }

    /**
     * @return void
     */
    public function testGetCustomersReturnsArray(): void
    {
        $expectedResult = [
            ['customer_id' => 1],
            ['customer_id' => 2]
        ];

        $queryMock = $this->createMock(\Zend_Db_Statement_Interface::class);
        $queryMock->method('fetchAll')
            ->willReturn($expectedResult);

        $selectMock = $this->createMock(\Magento\Framework\DB\Select::class);
        $selectMock->method('where')
            ->willReturnSelf();
        $selectMock->method('group')
            ->willReturnSelf();
        $selectMock->method('query')
            ->willReturn($queryMock);

        $this->collection->method('getSelect')
            ->willReturn($selectMock);

        $result = $this->quoteAbandoned->getCustomers();
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return void
     */
    public function testIsNotificationReturnsNotificationValue(): void
    {
        $quoteId = 1;
        $expectedNotification = 1;
        $this->abandonedCartData->method('getDataAbandonedCart')
            ->willReturn([['notification' => $expectedNotification]]);

        $result = $this->quoteAbandoned->isNotification($quoteId);
        $this->assertSame($expectedNotification, $result);
    }

    /**
     * @return void
     */
    public function testIsNotificationReturnsZeroWhenNoData(): void
    {
        $quoteId = 1;
        $this->abandonedCartData->method('getDataAbandonedCart')
            ->willReturn([]);

        $result = $this->quoteAbandoned->isNotification($quoteId);
        $this->assertSame(0, $result);
    }

}
