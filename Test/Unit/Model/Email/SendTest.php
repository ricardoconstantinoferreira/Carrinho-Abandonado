<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Test\Unit\Model\Email;

use Magento\Customer\Model\Url;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use RCFerreira\AbandonedCart\Helper\HelperData;
use RCFerreira\AbandonedCart\Logger\Logger;
use PHPUnit\Framework\TestCase;
use RCFerreira\AbandonedCart\Model\Email\Send;
use Magento\Customer\Api\Data\CustomerInterface;

class SendTest extends TestCase
{

    /**
     * @var StateInterface|(StateInterface&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private StateInterface $stateInterface;

    /**
     * @var Escaper|(Escaper&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private Escaper $escaper;

    /**
     * @var TransportBuilder|(TransportBuilder&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private TransportBuilder $transportBuilder;

    /**
     * @var Url|(Url&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private Url $url;

    /**
     * @var HelperData|\PHPUnit\Framework\MockObject\MockObject|(HelperData&\PHPUnit\Framework\MockObject\MockObject)
     */
    private HelperData $helperData;

    /**
     * @var StoreManagerInterface|(StoreManagerInterface&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Logger|\PHPUnit\Framework\MockObject\MockObject|(Logger&\PHPUnit\Framework\MockObject\MockObject)
     */
    private Logger $logger;

    /**
     * @var Send
     */
    private Send $send;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->stateInterface = $this->getMockBuilder(StateInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->escaper = $this->getMockBuilder(Escaper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transportBuilder = $this->getMockBuilder(TransportBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->url = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperData = $this->getMockBuilder(HelperData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManager = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->logger = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->send = new Send(
            $this->stateInterface,
            $this->escaper,
            $this->transportBuilder,
            $this->url,
            $this->helperData,
            $this->storeManager,
            $this->logger
        );
    }

    /**
     * @return void
     */
    public function testSendMailSuccess(): void
    {
        $customer = $this->createMock(CustomerInterface::class);
        $customer->method('getFirstname')->willReturn('John');
        $customer->method('getLastname')->willReturn('Doe');
        $customer->method('getEmail')->willReturn('john.doe@example.com');

        $this->helperData->method('getCartName')->willReturn('CartName');
        $this->helperData->method('getCartEmail')->willReturn('cart@example.com');

        $this->escaper->method('escapeHtml')->will($this->returnArgument(0));

        $store = $this->createMock(\Magento\Store\Api\Data\StoreInterface::class);
        $store->method('getId')->willReturn(1);
        $this->storeManager->method('getStore')->willReturn($store);

        $transport = $this->createMock(\Magento\Framework\Mail\TransportInterface::class);
        $transport->expects($this->once())->method('sendMessage');

        $this->transportBuilder->method('setTemplateIdentifier')->willReturnSelf();
        $this->transportBuilder->method('setTemplateOptions')->willReturnSelf();
        $this->transportBuilder->method('setTemplateVars')->willReturnSelf();
        $this->transportBuilder->method('setFrom')->willReturnSelf();
        $this->transportBuilder->method('addTo')->willReturnSelf();
        $this->transportBuilder->method('getTransport')->willReturn($transport);

        $this->stateInterface->expects($this->once())->method('suspend');
        $this->stateInterface->expects($this->once())->method('resume');

        $this->send->sendMail($customer);
    }

    /**
     * @return void
     */
    public function testSendMailException(): void
    {
        $customer = $this->createMock(CustomerInterface::class);
        $customer->method('getFirstname')->willReturn('John');
        $customer->method('getLastname')->willReturn('Doe');
        $customer->method('getEmail')->willReturn('john.doe@example.com');

        $this->helperData->method('getCartName')->willReturn('CartName');
        $this->helperData->method('getCartEmail')->willReturn('cart@example.com');

        $this->escaper->method('escapeHtml')->will($this->returnArgument(0));

        $this->storeManager->method('getStore')->willReturn($this->createMock(\Magento\Store\Api\Data\StoreInterface::class));

        $this->transportBuilder->method('setTemplateIdentifier')->willReturnSelf();
        $this->transportBuilder->method('setTemplateOptions')->willReturnSelf();
        $this->transportBuilder->method('setTemplateVars')->willReturnSelf();
        $this->transportBuilder->method('setFrom')->willReturnSelf();
        $this->transportBuilder->method('addTo')->willReturnSelf();
        $this->transportBuilder->method('getTransport')->will($this->throwException(new \Exception('Mail error')));

        $this->stateInterface->expects($this->once())->method('suspend');
        $this->stateInterface->expects($this->any())->method('resume');

        $this->logger->expects($this->once())->method('info')->with('Mail error');

        $this->send->sendMail($customer);
    }
}
