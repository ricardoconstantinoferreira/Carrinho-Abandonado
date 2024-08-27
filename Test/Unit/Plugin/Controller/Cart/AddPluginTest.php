<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Plugin\Controller\Cart;

use PHPUnit\Framework\TestCase;
use Magento\Checkout\Controller\Cart\Add;
use RCFerreira\AbandonedCart\Api\AbandonedCartRepositoryInterface;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;
use RCFerreira\AbandonedCart\Logger\Logger;
use RCFerreira\AbandonedCart\Model\Data\AbandonedCartData;
use RCFerreira\AbandonedCart\Plugin\Controller\Cart\AddPlugin;
use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;

class AddPluginTest extends TestCase
{

    private AbandonedCartInterface $abandonedCart;

    private AbandonedCartRepositoryInterface $abandonedCartRepository;

    private Logger $logger;

    private CheckoutSessionFactory $checkoutSessionFactory;

    private AbandonedCartData $abandonedCartData;

    private AddPlugin $addPlugin;

    public function setUp(): void
    {
        $this->abandonedCart = $this->getMockBuilder(AbandonedCartInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartRepository = $this->getMockBuilder(AbandonedCartRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->logger = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->checkoutSessionFactory = $this->getMockBuilder(CheckoutSessionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abandonedCartData = $this->getMockBuilder(AbandonedCartData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->addPlugin = new AddPlugin(
            $this->abandonedCart,
            $this->abandonedCartRepository,
            $this->logger,
            $this->checkoutSessionFactory,
            $this->abandonedCartData
        );
    }

    public function testAfterExecuteSaveAbandonedCart(): void
    {
        $checkoutSession = $this->createMock(\Magento\Checkout\Model\Session::class);
        $quote = $this->createMock(\Magento\Quote\Model\Quote::class);

        $checkoutSession->method('getQuote')->willReturn($quote);
        $quote->method('getId')->willReturn(123);

        $this->checkoutSessionFactory->method('create')->willReturn($checkoutSession);
        $this->abandonedCartData->method('getDataAbandonedCart')->with(123)->willReturn([]);

        $this->abandonedCart->expects($this->once())->method('setQuote')->with(123);
        $this->abandonedCart->expects($this->once())->method('setNotification')->with(1);
        $this->abandonedCartRepository->expects($this->once())->method('save')->with($this->abandonedCart);

        $result = $this->addPlugin->afterExecute(
            $this->createMock(Add::class),
            'some_result'
        );

        $this->assertEquals('some_result', $result);
    }

    public function testAfterExecuteExceptionLogging(): void
    {
        $checkoutSession = $this->createMock(\Magento\Checkout\Model\Session::class);
        $quote = $this->createMock(\Magento\Quote\Model\Quote::class);

        $checkoutSession->method('getQuote')->willReturn($quote);
        $quote->method('getId')->willReturn(123);

        $this->checkoutSessionFactory->method('create')->willReturn($checkoutSession);
        $this->abandonedCartData->method('getDataAbandonedCart')->with(123)->willReturn([]);

        $this->abandonedCart->method('setQuote')->willThrowException(new \Exception('Save failed'));

        $this->logger->expects($this->once())->method('info')->with($this->stringContains('Notification Save failed'));

        $result = $this->addPlugin->afterExecute(
            $this->createMock(Add::class),
            'some_result'
        );

        $this->assertEquals('some_result', $result);
    }
}
