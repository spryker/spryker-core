<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerDataChangeRequest\Communication\Plugin\Customer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer;
use ReflectionClass;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestTypeEnum;
use Spryker\Zed\CustomerDataChangeRequest\Business\CustomerDataChangeRequestFacade;
use Spryker\Zed\CustomerDataChangeRequest\Communication\Plugin\Customer\EmailChangeRequestSendVerificationCustomerPreUpdatePlugin;
use SprykerTest\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerDataChangeRequest
 * @group Communication
 * @group Plugin
 * @group Customer
 * @group EmailChangeRequestSendVerificationCustomerPreUpdatePluginTest
 * Add your own group annotations below this line
 */
class EmailChangeRequestSendVerificationCustomerPreUpdatePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestCommunicationTester
     */
    protected CustomerDataChangeRequestCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPreUpdateReturnsCustomerTransferWhenChangeRequestExists(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransferWithNewEmail();
        $customerTransfer->setChangeRequest(
            (new CustomerDataChangeRequestTransfer())->setType(CustomerDataChangeRequestTypeEnum::EMAIL->value),
        );

        $plugin = new EmailChangeRequestSendVerificationCustomerPreUpdatePlugin();

        // Act
        $resultCustomerTransfer = $plugin->preUpdate($customerTransfer);

        // Assert
        $this->assertSame($customerTransfer, $resultCustomerTransfer);
        $this->assertNull($resultCustomerTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testPreUpdateSetsMessageWhenVerificationIsSent(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransferWithNewEmail();

        $facadeMock = $this->createFacadeMock(true);
        $plugin = $this->createPluginWithFacade($facadeMock);

        // Act
        $resultCustomerTransfer = $plugin->preUpdate($customerTransfer);

        // Assert
        $this->assertSame('customer.change_customer_email_mail_sent', $resultCustomerTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testPreUpdateDoesNotSetMessageWhenVerificationIsNotSent(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransferWithNewEmail();

        $facadeMock = $this->createFacadeMock(false);
        $plugin = $this->createPluginWithFacade($facadeMock);

        // Act
        $resultCustomerTransfer = $plugin->preUpdate($customerTransfer);

        // Assert
        $this->assertNull($resultCustomerTransfer->getMessage());
    }

    /**
     * @param bool $isSent
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Business\CustomerDataChangeRequestFacade
     */
    protected function createFacadeMock(bool $isSent): CustomerDataChangeRequestFacade
    {
        $facadeMock = $this->getMockBuilder(CustomerDataChangeRequestFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $facadeMock
            ->method('sendVerificationEmail')
            ->with($this->isInstanceOf(CustomerTransfer::class))
            ->willReturn(
                (new VerificationTokenCustomerChangeDataResponseTransfer())->setIsSent($isSent),
            );

        return $facadeMock;
    }

    /**
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\CustomerDataChangeRequestFacade $facade
     *
     * @return \Spryker\Zed\CustomerDataChangeRequest\Communication\Plugin\Customer\EmailChangeRequestSendVerificationCustomerPreUpdatePlugin
     */
    protected function createPluginWithFacade(CustomerDataChangeRequestFacade $facade): EmailChangeRequestSendVerificationCustomerPreUpdatePlugin
    {
        $plugin = new EmailChangeRequestSendVerificationCustomerPreUpdatePlugin();
        $reflection = new ReflectionClass($plugin);
        $property = $reflection->getProperty('facade');
        $property->setAccessible(true);
        $property->setValue($plugin, $facade);

        return $plugin;
    }
}
