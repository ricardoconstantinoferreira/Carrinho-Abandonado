<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Model\Data;

use Magento\Framework\Api\SearchCriteriaBuilder;
use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Api\AbandonedCartRepositoryInterface;
use RCFerreira\AbandonedCart\Model\Data\AbandonedCartData;
use Magento\Framework\Api\SearchCriteria;

class AbandonedCartDataTest extends TestCase
{
    private  $searchCriteriaBuilder;

    private  $abandonedCartRepository;

    private $abandonedCartData;

    public function setUp(): void
    {
        $this->searchCriteriaBuilder = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartRepository = $this->getMockBuilder(AbandonedCartRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartData = new AbandonedCartData(
            $this->searchCriteriaBuilder,
            $this->abandonedCartRepository
        );
    }

    public function testGetDataAbandonedCart(): void
    {
        $quoteId = 123;
        $searchCriteria = $this->createMock(SearchCriteria::class);
        $searchResults = [];

        $this->searchCriteriaBuilder
            ->expects(self::once())
            ->method('addFilter')
            ->with('quote_id', $quoteId)
            ->willReturnSelf();

        $this->searchCriteriaBuilder
            ->expects(self::once())
            ->method('create')
            ->willReturn($searchCriteria);

        $this->abandonedCartRepository
            ->expects(self::once())
            ->method('getList')
            ->with($searchCriteria)
            ->willReturn($searchResults);

        $result = $this->abandonedCartData->getDataAbandonedCart($quoteId);
        self::assertSame($searchResults, $result);
    }

}
