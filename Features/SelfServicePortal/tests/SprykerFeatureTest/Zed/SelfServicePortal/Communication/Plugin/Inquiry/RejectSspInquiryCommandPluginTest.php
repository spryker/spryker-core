<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Inquiry;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver\SspInquiryRejectionHandler;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\StateMachine\RejectSspInquiryCommandPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Inquiry
 * @group RejectSspInquiryCommandPluginTest
 *
 * Add your own group annotations below this line
 */
class RejectSspInquiryCommandPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Store\StoreDependencyProvider::STORE_CURRENT
     *
     * @var string
     */
    protected const STORE_CURRENT = 'STORE_CURRENT';

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\StateMachine\RejectSspInquiryCommandPlugin
     */
    protected $rejectSspInquiryCommandPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade
     */
    protected $selfServicePortalFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    protected $mailFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected CustomerFacadeInterface $customerFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig
     */
    protected $selfServicePortalConfigMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory
     */
    protected $selfServicePortalInquiryBusinessFactoryMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->selfServicePortalFacadeMock = $this->createMock(SelfServicePortalFacade::class);
        $this->mailFacadeMock = $this->createMock(MailFacadeInterface::class);
        $this->customerFacadeMock = $this->createMock(CustomerFacadeInterface::class);
        $this->selfServicePortalConfigMock = $this->createMock(SelfServicePortalConfig::class);

        $this->selfServicePortalInquiryBusinessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->onlyMethods(['getMailFacade', 'getConfig', 'getCustomerFacade', 'createSspInquiryReader', 'createSspInquiryRejectionHandler'])
            ->getMock();
        $sspInquiryReaderMock = $this->createMock(SspInquiryReaderInterface::class);
        $sspInquiryCollectionTransfer = (new SspInquiryCollectionTransfer())
            ->addSspInquiry(
                (new SspInquiryTransfer())->setCompanyUser(
                    (new CompanyUserTransfer())
                        ->setIdCompanyUser(1)
                        ->setCustomer((new CustomerTransfer())->setIdCustomer(1)),
                ),
            );
        $sspInquiryReaderMock->method('getSspInquiryCollection')->willReturn($sspInquiryCollectionTransfer);
        $this->selfServicePortalInquiryBusinessFactoryMock->method('createSspInquiryReader')->willReturn($sspInquiryReaderMock);

        $this->selfServicePortalInquiryBusinessFactoryMock->method('getMailFacade')->willReturn($this->mailFacadeMock);
        $this->selfServicePortalInquiryBusinessFactoryMock->method('getCustomerFacade')->willReturn($this->customerFacadeMock);
        $sspInquiryRejectionHandler = new SspInquiryRejectionHandler(
            $sspInquiryReaderMock,
            $this->mailFacadeMock,
            $this->customerFacadeMock,
            $this->selfServicePortalConfigMock,
        );

        $this->selfServicePortalInquiryBusinessFactoryMock->method('createSspInquiryRejectionHandler')->willReturn($sspInquiryRejectionHandler);

        $this->rejectSspInquiryCommandPlugin = new RejectSspInquiryCommandPlugin();
        $this->rejectSspInquiryCommandPlugin->setBusinessFactory($this->selfServicePortalInquiryBusinessFactoryMock);
        $this->rejectSspInquiryCommandPlugin->setFacade($this->selfServicePortalFacadeMock);
        $this->rejectSspInquiryCommandPlugin->setConfig($this->selfServicePortalConfigMock);
    }

    /**
     * @return void
     */
    public function testRun(): void
    {
        // Arrange
        $stateMachineItemTransfer = (new StateMachineItemTransfer())->setIdentifier(1);

        $this->selfServicePortalConfigMock->expects($this->once())
            ->method('getYvesBaseUrl')
            ->willReturn('http://example.com');

        $this->mailFacadeMock->expects($this->once())
            ->method('handleMail')
            ->with($this->isInstanceOf(MailTransfer::class));

        // Act
        $this->rejectSspInquiryCommandPlugin->run($stateMachineItemTransfer);
    }
}
