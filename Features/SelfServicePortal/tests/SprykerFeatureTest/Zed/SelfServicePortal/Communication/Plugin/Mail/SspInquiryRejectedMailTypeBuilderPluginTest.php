<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Mail;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Mail\SspInquiryRejectedMailTypeBuilderPlugin;
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
 * @group SspInquiryRejectedMailTypeBuilderPluginTest
 *
 * Add your own group annotations below this line
 */
class SspInquiryRejectedMailTypeBuilderPluginTest extends Unit
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Mail\SspInquiryRejectedMailTypeBuilderPlugin
     */
    protected $sspInquiryRejectedMailTypeBuilderPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig
     */
    protected $selfServicePortalConfigMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->selfServicePortalConfigMock = $this->createMock(SelfServicePortalConfig::class);

        $this->sspInquiryRejectedMailTypeBuilderPlugin = new SspInquiryRejectedMailTypeBuilderPlugin();
        $this->sspInquiryRejectedMailTypeBuilderPlugin->setConfig($this->selfServicePortalConfigMock);
    }

    /**
     * @return void
     */
    public function testBuild(): void
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())->setEmail('test@example.com');
        $mailTransfer = (new MailTransfer())
            ->setSspInquiry(new SspInquiryTransfer())
            ->setCustomer($customerTransfer);

        $this->selfServicePortalConfigMock->expects($this->once())
            ->method('getYvesBaseUrl')
            ->willReturn('http://example.com');

        // Act
        $resultMailTransfer = $this->sspInquiryRejectedMailTypeBuilderPlugin->build($mailTransfer);

        // Assert
        $this->assertSame('self_service_portal.inquiry.mail.trans.ssp_inquiry_rejected.subject', $resultMailTransfer->getSubject());
        $this->assertSame('http://example.com/customer/ssp-inquiry', $resultMailTransfer->getSspInquiryUrl());
        $this->assertCount(2, $resultMailTransfer->getTemplates());
        $this->assertCount(1, $resultMailTransfer->getRecipients());
        $this->assertSame('test@example.com', $resultMailTransfer->getRecipients()[0]->getEmail());
    }
}
