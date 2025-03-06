<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspInquiry\Communication\Plugin\SspInquiry;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacade;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\SspInquiryManagement\RejectSspInquiryCommandPlugin;
use SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class RejectSspInquiryCommandPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Store\StoreDependencyProvider::STORE_CURRENT
     *
     * @var string
     */
    protected const STORE_CURRENT = 'STORE_CURRENT';

    /**
     * @var \SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\SspInquiryManagement\RejectSspInquiryCommandPlugin
     */
    protected $rejectSspInquiryCommandPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface
     */
    protected $sspInquiryManagementFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    protected $mailFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig
     */
    protected $sspInquiryManagementConfigMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory
     */
    protected $sspInquiryCommunicationFactoryMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sspInquiryManagementFacadeMock = $this->createMock(SspInquiryManagementFacade::class);
        $this->mailFacadeMock = $this->createMock(MailFacadeInterface::class);
        $this->sspInquiryManagementConfigMock = $this->createMock(SspInquiryManagementConfig::class);

        $this->sspInquiryCommunicationFactoryMock = $this->getMockBuilder(SspInquiryManagementCommunicationFactory::class)
            ->onlyMethods(['getFacade', 'getMailFacade', 'getConfig'])
            ->getMock();

        $this->sspInquiryCommunicationFactoryMock->method('getMailFacade')->willReturn($this->mailFacadeMock);

        $this->rejectSspInquiryCommandPlugin = new RejectSspInquiryCommandPlugin();
        $this->rejectSspInquiryCommandPlugin->setFactory($this->sspInquiryCommunicationFactoryMock);
        $this->rejectSspInquiryCommandPlugin->setFacade($this->sspInquiryManagementFacadeMock);
        $this->rejectSspInquiryCommandPlugin->setConfig($this->sspInquiryManagementConfigMock);
    }

    /**
     * @return void
     */
    public function testRun(): void
    {
        // Arrange
        $stateMachineItemTransfer = (new StateMachineItemTransfer())->setIdentifier(1);
        $sspInquiryCollectionTransfer = (new SspInquiryCollectionTransfer())->addSspInquiry(
            (new SspInquiryTransfer())->setCompanyUser(
                (new CompanyUserTransfer())
                    ->setIdCompanyUser(1),
            ),
        );

        $this->sspInquiryManagementFacadeMock->expects($this->once())
            ->method('getSspInquiryCollection')
            ->with($this->isInstanceOf(SspInquiryCriteriaTransfer::class))
            ->willReturn($sspInquiryCollectionTransfer);

        $this->sspInquiryManagementConfigMock->expects($this->once())
            ->method('getYvesBaseUrl')
            ->willReturn('http://example.com');

        $this->mailFacadeMock->expects($this->once())
            ->method('handleMail')
            ->with($this->isInstanceOf(MailTransfer::class));

        // Act
        $this->rejectSspInquiryCommandPlugin->run($stateMachineItemTransfer);
    }
}
