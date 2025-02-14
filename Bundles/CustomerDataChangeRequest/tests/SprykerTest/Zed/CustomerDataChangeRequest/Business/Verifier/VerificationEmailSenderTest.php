<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerDataChangeRequest\Business\Verifier;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerCriteriaTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\CustomerDataChangeRequest\Business\Mail\CustomerDataChangeRequestMailSenderInterface;
use Spryker\Zed\CustomerDataChangeRequest\Business\Verifier\VerificationEmailSender;
use Spryker\Zed\CustomerDataChangeRequest\Business\Writer\CustomerDataChangeRequestWriterInterface;
use Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToUtilTextServiceInterface;
use SprykerTest\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerDataChangeRequest
 * @group Business
 * @group Verifier
 * @group VerificationEmailSenderTest
 * Add your own group annotations below this line
 */
class VerificationEmailSenderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestBusinessTester
     */
    protected CustomerDataChangeRequestBusinessTester $tester;

    /**
     * @return void
     */
    public function testSendReturnsSuccessWhenEmailIsDifferent(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransferWithNewEmail();
        $existingCustomerTransfer = $this->tester->createTestCustomerTransfer();

        $customerResponseTransfer = (new CustomerResponseTransfer())
            ->setCustomerTransfer($existingCustomerTransfer);

        $verificationEmailSender = $this->createVerificationEmailSender(
            $this->createCustomerFacadeMock($customerResponseTransfer),
            $this->createMailSenderMock(),
            $this->createConfigMock(),
            $this->createUtilTextServiceMock(),
            $this->createWriterMock(),
        );

        // Act
        $response = $verificationEmailSender->send($customerTransfer);

        // Assert
        $this->assertTrue($response->getIsSent());
    }

    /**
     * @return void
     */
    public function testSendReturnsFailureWhenEmailIsSame(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $existingCustomerTransfer = $this->tester->createTestCustomerTransfer();

        $customerResponseTransfer = (new CustomerResponseTransfer())
            ->setCustomerTransfer($existingCustomerTransfer);

        $verificationEmailSender = $this->createVerificationEmailSender(
            $this->createCustomerFacadeMock($customerResponseTransfer),
            $this->createMailSenderMock(false),
            $this->createConfigMock(),
            $this->createUtilTextServiceMock(),
            $this->createWriterMock(false),
        );

        // Act
        $response = $verificationEmailSender->send($customerTransfer);

        // Assert
        $this->assertFalse($response->getIsSent());
    }

    /**
     * @return void
     */
    public function testSendReturnsFailureWhenCustomerNotFound(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransferWithNewEmail();

        $customerResponseTransfer = (new CustomerResponseTransfer())
            ->setCustomerTransfer(null);

        $verificationEmailSender = $this->createVerificationEmailSender(
            $this->createCustomerFacadeMock($customerResponseTransfer),
            $this->createMailSenderMock(false),
            $this->createConfigMock(),
            $this->createUtilTextServiceMock(),
            $this->createWriterMock(false),
        );

        // Act
        $response = $verificationEmailSender->send($customerTransfer);

        // Assert
        $this->assertFalse($response->getIsSent());
    }

    /**
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\Mail\CustomerDataChangeRequestMailSenderInterface $mailSender
     * @param \Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig $config
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\Writer\CustomerDataChangeRequestWriterInterface $writer
     *
     * @return \Spryker\Zed\CustomerDataChangeRequest\Business\Verifier\VerificationEmailSender
     */
    protected function createVerificationEmailSender(
        CustomerDataChangeRequestToCustomerFacadeInterface $customerFacade,
        CustomerDataChangeRequestMailSenderInterface $mailSender,
        CustomerDataChangeRequestConfig $config,
        CustomerDataChangeRequestToUtilTextServiceInterface $utilTextService,
        CustomerDataChangeRequestWriterInterface $writer
    ): VerificationEmailSender {
        return new VerificationEmailSender(
            $customerFacade,
            $mailSender,
            $config,
            $utilTextService,
            $writer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer|null $customerResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface
     */
    protected function createCustomerFacadeMock(
        ?CustomerResponseTransfer $customerResponseTransfer = null
    ): MockObject|CustomerDataChangeRequestToCustomerFacadeInterface {
        $customerFacadeMock = $this->createMock(CustomerDataChangeRequestToCustomerFacadeInterface::class);
        $customerFacadeMock
            ->method('getCustomerByCriteria')
            ->with($this->isInstanceOf(CustomerCriteriaTransfer::class))
            ->willReturn($customerResponseTransfer ?? new CustomerResponseTransfer());

        return $customerFacadeMock;
    }

    /**
     * @param bool $shouldBeCalled
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Business\Mail\CustomerDataChangeRequestMailSenderInterface
     */
    protected function createMailSenderMock(bool $shouldBeCalled = true): MockObject|CustomerDataChangeRequestMailSenderInterface
    {
        $mailSenderMock = $this->createMock(CustomerDataChangeRequestMailSenderInterface::class);

        if ($shouldBeCalled) {
            $mailSenderMock
                ->expects($this->once())
                ->method('sendEmailChangeVerificationToken')
                ->with(
                    $this->isInstanceOf(CustomerTransfer::class),
                    $this->isType('string'),
                );
        } else {
            $mailSenderMock
                ->expects($this->never())
                ->method('sendEmailChangeVerificationToken');
        }

        return $mailSenderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig
     */
    protected function createConfigMock(): MockObject|CustomerDataChangeRequestConfig
    {
        $configMock = $this->createMock(CustomerDataChangeRequestConfig::class);
        $configMock
            ->method('getEmailChaneTokenUrl')
            ->willReturn('http://example.com/verify');

        return $configMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToUtilTextServiceInterface
     */
    protected function createUtilTextServiceMock(): MockObject|CustomerDataChangeRequestToUtilTextServiceInterface
    {
        $utilTextServiceMock = $this->createMock(CustomerDataChangeRequestToUtilTextServiceInterface::class);
        $utilTextServiceMock
            ->method('generateRandomString')
            ->willReturn(CustomerDataChangeRequestBusinessTester::TEST_VERIFICATION_TOKEN);

        return $utilTextServiceMock;
    }

    /**
     * @param bool $shouldBeCalled
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Business\Writer\CustomerDataChangeRequestWriterInterface
     */
    protected function createWriterMock(bool $shouldBeCalled = true): MockObject|CustomerDataChangeRequestWriterInterface
    {
        $writerMock = $this->createMock(CustomerDataChangeRequestWriterInterface::class);

        if ($shouldBeCalled) {
            $writerMock
                ->expects($this->once())
                ->method('saveEmailChangeRequest')
                ->with(
                    $this->isInstanceOf(CustomerTransfer::class),
                    $this->isType('string'),
                );
        } else {
            $writerMock
                ->expects($this->never())
                ->method('saveEmailChangeRequest');
        }

        return $writerMock;
    }
}
