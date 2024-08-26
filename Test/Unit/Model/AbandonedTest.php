<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Model\Abandoned;
use RCFerreira\AbandonedCart\Model\Data\ParametersAbandoned;
use RCFerreira\AbandonedCart\Model\Email\Send;

class AbandonedTest extends TestCase
{

    /**
     * @var (CustomerRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private $customerRepositoryInterface;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(ParametersAbandoned&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $parametersAbandoned;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|(Send&\PHPUnit\Framework\MockObject\MockObject)
     */
    private $send;

    /**
     * @var Abandoned
     */
    private $abandoned;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->customerRepositoryInterface = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->parametersAbandoned = $this->getMockBuilder(ParametersAbandoned::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->send = $this->getMockBuilder(Send::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandoned = new Abandoned(
            $this->customerRepositoryInterface,
            $this->parametersAbandoned,
            $this->send
        );
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function testCheck()
    {
        $customerId = 123;
        $customer = $this->createMock(CustomerInterface::class);

        $this->customerRepositoryInterface
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);

        $this->parametersAbandoned
            ->expects(self::once())
            ->method('execute')
            ->with($customerId)
            ->willReturn(true);

        $this->send
            ->expects(self::once())
            ->method('sendMail')
            ->with($customer);

        $this->parametersAbandoned
            ->expects(self::once())
            ->method('setInvalidNotification')
            ->with($customerId);

        $this->abandoned->check($customerId);
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function testCheckException()
    {
        $customerId = 123;

        $this->customerRepositoryInterface
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willThrowException(new \Exception('Test Exception'));

        $this->parametersAbandoned
            ->expects(self::never())
            ->method('execute');

        $this->send
            ->expects(self::never())
            ->method('sendMail');

        $this->parametersAbandoned
            ->expects(self::never())
            ->method('setInvalidNotification');

        $this->expectException(LocalizedException::class);
        $this->abandoned->check($customerId);
    }
}
