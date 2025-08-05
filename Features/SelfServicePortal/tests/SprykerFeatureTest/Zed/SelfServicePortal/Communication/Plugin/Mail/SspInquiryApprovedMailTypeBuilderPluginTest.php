<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Mail;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Mail\SspInquiryApprovedMailTypeBuilderPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Mail
 * @group SspInquiryApprovedMailTypeBuilderPluginTest
 *
 * Add your own group annotations below this line
 */
class SspInquiryApprovedMailTypeBuilderPluginTest extends Unit
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Mail\SspInquiryApprovedMailTypeBuilderPlugin
     */
    protected $sspInquiryApprovedMailTypeBuilderPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig
     */
    protected $selfServicePortalConfig;

    protected function setUp(): void
    {
        parent::setUp();

        $this->selfServicePortalConfig = $this->createMock(SelfServicePortalConfig::class);

        $this->sspInquiryApprovedMailTypeBuilderPlugin = new SspInquiryApprovedMailTypeBuilderPlugin();
        $this->sspInquiryApprovedMailTypeBuilderPlugin->setConfig($this->selfServicePortalConfig);
    }

    public function testGetName(): void
    {
        // Act
        $result = $this->sspInquiryApprovedMailTypeBuilderPlugin->getName();

        // Assert
        $this->assertSame('ssp inquiry approved', $result);
    }

    public function testBuild(): void
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())->setEmail('test@example.com');
        $companyUserTransfer = (new CompanyUserTransfer())->setCustomer($customerTransfer);
        $sspInquiryTransfer = (new SspInquiryTransfer())->setCompanyUser($companyUserTransfer)->setReference('SSP-INQUIRY-123');
        $mailTransfer = (new MailTransfer())->setSspInquiry($sspInquiryTransfer);

        $this->selfServicePortalConfig->expects($this->once())
            ->method('getYvesBaseUrl')
            ->willReturn('http://example.com');

        // Act
        $resultMailTransfer = $this->sspInquiryApprovedMailTypeBuilderPlugin->build($mailTransfer);

        // Assert
        $this->assertSame('self_service_portal.inquiry.mail.trans.ssp_inquiry_approved.subject', $resultMailTransfer->getSubject());
        $this->assertSame('http://example.com/customer/ssp-inquiry', $resultMailTransfer->getSspInquiryUrl());
        $this->assertCount(2, $resultMailTransfer->getTemplates());
        $this->assertCount(1, $resultMailTransfer->getRecipients());
        $this->assertSame('test@example.com', $resultMailTransfer->getRecipients()[0]->getEmail());
    }
}
