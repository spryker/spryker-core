<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerDataChangeRequest\Business\Customer\Strategy;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestStatusEnum;
use Spryker\Zed\CustomerDataChangeRequest\Business\Customer\Strategy\EmailConfirmCustomerDataChangeRequestStrategy;
use Spryker\Zed\CustomerDataChangeRequest\Business\Logger\AuditLoggerInterface;
use Spryker\Zed\CustomerDataChangeRequest\Business\Notifier\NotificationEmailSenderInterface;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToGlossaryFacadeInterface;
use Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface;
use Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface;
use SprykerTest\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerDataChangeRequest
 * @group Business
 * @group Customer
 * @group Strategy
 * @group EmailConfirmCustomerDataChangeRequestStrategyTest
 * Add your own group annotations below this line
 */
class EmailConfirmCustomerDataChangeRequestStrategyTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestBusinessTester
     */
    protected CustomerDataChangeRequestBusinessTester $tester;

    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueForEmailType(): void
    {
        // Arrange
        $customerDataChangeRequestTransfer = $this->tester->createTestEmailChangeRequestTransfer();
        $strategy = $this->createStrategy(
            $this->createRepositoryMock(),
            $this->createEntityManagerMock(),
            $this->createCustomerFacadeMock(),
            $this->createAuditLoggerMock(),
            $this->createGlossaryFacadeMock(),
            $this->createNotificationEmailSenderMock(),
        );

        // Act
        $isApplicable = $strategy->isApplicable($customerDataChangeRequestTransfer);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseForNonEmailType(): void
    {
        // Arrange
        $customerDataChangeRequestTransfer = $this->tester->createTestNonEmailChangeRequestTransfer();
        $strategy = $this->createStrategy(
            $this->createRepositoryMock(),
            $this->createEntityManagerMock(),
            $this->createCustomerFacadeMock(),
            $this->createAuditLoggerMock(),
            $this->createGlossaryFacadeMock(),
            $this->createNotificationEmailSenderMock(),
        );

        // Act
        $isApplicable = $strategy->isApplicable($customerDataChangeRequestTransfer);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testExecuteReturnsErrorForInvalidRequest(): void
    {
        // Arrange
        $customerDataChangeRequestTransfer = $this->tester->createTestEmailChangeRequestTransfer();

        $glossaryFacadeMock = $this->createGlossaryFacadeMock();
        $glossaryFacadeMock->expects($this->once())
            ->method('translate')
            ->willReturn('Invalid request');

        $strategy = $this->createStrategy(
            $this->createRepositoryMock(),
            $this->createEntityManagerMock(),
            $this->createCustomerFacadeMock(),
            $this->createAuditLoggerMock(),
            $glossaryFacadeMock,
            $this->createNotificationEmailSenderMock(),
        );

        // Act
        $response = $strategy->execute($customerDataChangeRequestTransfer);

        // Assert
        $this->assertNotEmpty($response->getErrors());
        $this->assertSame('Invalid request', $response->getErrors()->offsetGet(0)->getMessage());
    }

    /**
     * @return void
     */
    public function testExecuteUpdatesCustomerAndMarksRequestAsCompleted(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $customerDataChangeRequestTransfer = $this->tester->createTestEmailChangeRequestTransfer();

        $entityManagerMock = $this->createEntityManagerMock();
        $entityManagerMock->expects($this->once())
            ->method('saveEmailCustomerDataChangeRequest')
            ->with($this->callback(function (CustomerDataChangeRequestTransfer $transfer) {
                return $transfer->getStatus() === CustomerDataChangeRequestStatusEnum::COMPLETED->value;
            }));

        $customerFacadeMock = $this->createCustomerFacadeMock($customerTransfer);
        $customerFacadeMock->expects($this->once())
            ->method('updateCustomer');

        $auditLoggerMock = $this->createAuditLoggerMock();
        $auditLoggerMock->expects($this->once())
            ->method('addSuccessfulEmailUpdateAuditLog');

        $notificationEmailSenderMock = $this->createNotificationEmailSenderMock();
        $notificationEmailSenderMock->expects($this->once())
            ->method('send');

        $strategy = $this->createStrategy(
            $this->createRepositoryMock($customerDataChangeRequestTransfer),
            $entityManagerMock,
            $customerFacadeMock,
            $auditLoggerMock,
            $this->createGlossaryFacadeMock(),
            $notificationEmailSenderMock,
        );

        // Act
        $response = $strategy->execute($customerDataChangeRequestTransfer);

        // Assert
        $this->assertEmpty($response->getErrors());
    }

    /**
     * @param \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface $repositoryMock
     * @param \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface $entityManagerMock
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface $customerFacadeMock
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\Logger\AuditLoggerInterface $auditLoggerMock
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToGlossaryFacadeInterface $glossaryFacadeMock
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\Notifier\NotificationEmailSenderInterface $notificationEmailSenderMock
     *
     * @return \Spryker\Zed\CustomerDataChangeRequest\Business\Customer\Strategy\EmailConfirmCustomerDataChangeRequestStrategy
     */
    protected function createStrategy(
        CustomerDataChangeRequestRepositoryInterface $repositoryMock,
        CustomerDataChangeRequestEntityManagerInterface $entityManagerMock,
        CustomerDataChangeRequestToCustomerFacadeInterface $customerFacadeMock,
        AuditLoggerInterface $auditLoggerMock,
        CustomerDataChangeRequestToGlossaryFacadeInterface $glossaryFacadeMock,
        NotificationEmailSenderInterface $notificationEmailSenderMock
    ): EmailConfirmCustomerDataChangeRequestStrategy {
        return new EmailConfirmCustomerDataChangeRequestStrategy(
            $repositoryMock,
            $entityManagerMock,
            $customerFacadeMock,
            $auditLoggerMock,
            $glossaryFacadeMock,
            $notificationEmailSenderMock,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer|null $customerDataChangeRequestTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface
     */
    protected function createRepositoryMock(
        ?CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer = null
    ): CustomerDataChangeRequestRepositoryInterface {
        $repositoryMock = $this->createMock(CustomerDataChangeRequestRepositoryInterface::class);
        $collection = new CustomerDataChangeRequestCollectionTransfer();

        if ($customerDataChangeRequestTransfer !== null) {
            $collection->addCustomerDataChangeRequest($customerDataChangeRequestTransfer);
        }

        $repositoryMock->method('get')
            ->willReturn($collection);

        return $repositoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface
     */
    protected function createEntityManagerMock(): CustomerDataChangeRequestEntityManagerInterface
    {
        return $this->createMock(CustomerDataChangeRequestEntityManagerInterface::class);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface
     */
    protected function createCustomerFacadeMock(?CustomerTransfer $customerTransfer = null): CustomerDataChangeRequestToCustomerFacadeInterface
    {
        $customerFacadeMock = $this->createMock(CustomerDataChangeRequestToCustomerFacadeInterface::class);
        if ($customerTransfer !== null) {
            $customerFacadeMock->method('getCustomerByCriteria')
                ->willReturn((new CustomerResponseTransfer())
                    ->setCustomerTransfer($customerTransfer));
        }

        return $customerFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Business\Logger\AuditLoggerInterface
     */
    protected function createAuditLoggerMock(): AuditLoggerInterface
    {
        return $this->createMock(AuditLoggerInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToGlossaryFacadeInterface
     */
    protected function createGlossaryFacadeMock(): CustomerDataChangeRequestToGlossaryFacadeInterface
    {
        return $this->createMock(CustomerDataChangeRequestToGlossaryFacadeInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomerDataChangeRequest\Business\Notifier\NotificationEmailSenderInterface
     */
    protected function createNotificationEmailSenderMock(): NotificationEmailSenderInterface
    {
        return $this->createMock(NotificationEmailSenderInterface::class);
    }
}
