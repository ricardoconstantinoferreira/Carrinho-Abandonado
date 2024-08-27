<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart as ResourceModelAbandonedCart;
use RCFerreira\AbandonedCart\Model\AbandonedCartRepository;
use RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart\CollectionFactory;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartSearchResultsInterfaceFactory;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class AbandonedCartRepositoryTest extends TestCase
{

    private ResourceModelAbandonedCart $resourceModelAbandonedCart;

    private CollectionFactory $collectionFactory;

    private CollectionProcessorInterface $collectionProcessor;

    private AbandonedCartSearchResultsInterfaceFactory $abandonedCartSearchResults;

    private AbandonedCartRepository $repository;

    private AbandonedCartInterface $abandonedCartInterface;

    public function setUp(): void
    {
        $this->resourceModelAbandonedCart = $this->getMockBuilder(ResourceModelAbandonedCart::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionProcessor = $this->getMockBuilder(CollectionProcessorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartSearchResults = $this->getMockBuilder(AbandonedCartSearchResultsInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartInterface = $this->getMockForAbstractClass(
            AbandonedCartInterface::class,
            [],
            '',
            true,
            true,
            true,
            [
                '__toArray'
            ]
        );

        $this->repository = new AbandonedCartRepository(
            $this->resourceModelAbandonedCart,
            $this->collectionFactory,
            $this->collectionProcessor,
            $this->abandonedCartSearchResults
        );
    }

    public function testSaveSuccess(): void
    {
        $abandonedCartModel = $this->getMockBuilder(\RCFerreira\AbandonedCart\Model\AbandonedCart::class)
            ->disableOriginalConstructor()
            ->getMock();

        $abandonedCartModel->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn(123);

        $this->resourceModelAbandonedCart->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(\Magento\Framework\Model\AbstractModel::class))
            ->willReturn(1);

        $result = $this->repository->save($abandonedCartModel);

        $this->assertEquals(123, $result);
    }

    public function testSaveFailure(): void
    {
        $this->expectException(CouldNotSaveException::class);
        $this->expectExceptionMessage('Error to save notification');

        $abandonedCartModel = $this->getMockBuilder(\RCFerreira\AbandonedCart\Model\AbandonedCart::class)
            ->disableOriginalConstructor()
            ->getMock();

        $abandonedCartModel->expects($this->any())
            ->method('getId')
            ->willReturn(123);

        $this->resourceModelAbandonedCart->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(\Magento\Framework\Model\AbstractModel::class))
            ->willThrowException(new \Exception('Database error'));

        $this->repository->save($abandonedCartModel);
    }

    public function testGetList(): void
    {
        $searchCriteria = $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class);
        $collection = $this->createMock(\RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart\Collection::class);
        $searchResults = $this->createMock(\Magento\Framework\Api\SearchResultsInterface::class);

        $this->collectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($collection);

        $this->collectionProcessor->expects($this->once())
            ->method('process')
            ->with($searchCriteria, $collection);

        $this->abandonedCartSearchResults->expects($this->once())
            ->method('create')
            ->willReturn($searchResults);

        $collection->method('getItems')->willReturn([]);
        $collection->method('getSize')->willReturn(0);

        $searchResults->expects($this->once())
            ->method('setSearchCriteria')
            ->with($searchCriteria);

        $searchResults->expects($this->once())
            ->method('setItems')
            ->with([]);

        $searchResults->expects($this->once())
            ->method('setTotalCount')
            ->with(0);

        $result = $this->repository->getList($searchCriteria);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
