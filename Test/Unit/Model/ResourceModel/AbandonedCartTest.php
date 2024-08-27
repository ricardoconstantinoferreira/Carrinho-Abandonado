<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Model\ResourceModel;

use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart as AbandonedCartResource;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;
use PHPUnit\Framework\MockObject\MockObject;

class AbandonedCartTest extends TestCase
{
    private MockObject $resourceModel;

    protected function setUp(): void
    {
        $this->resourceModel = $this->getMockBuilder(AbandonedCartResource::class)
            ->disableOriginalConstructor()
            ->setMethods(['_init'])
            ->getMock();
    }

    public function testConstructCallsInitWithCorrectParameters(): void
    {
        $this->resourceModel->expects($this->once())
            ->method('_init')
            ->with(
                $this->equalTo(AbandonedCartInterface::TABLE),
                $this->equalTo(AbandonedCartInterface::ENTITY_ID)
            );

        $this->resourceModel->_construct();
    }
}
