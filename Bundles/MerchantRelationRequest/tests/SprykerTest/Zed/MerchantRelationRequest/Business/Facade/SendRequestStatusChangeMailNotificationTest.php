<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestBusinessFactory;
use Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacade;
use Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMailFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestDependencyProvider;
use SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationRequest
 * @group Business
 * @group Facade
 * @group SendRequestStatusChangeMailNotificationTest
 * Add your own group annotations below this line
 */
class SendRequestStatusChangeMailNotificationTest extends Unit
{
    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_REJECTED
     *
     * @var string
     */
    protected const STATUS_REJECTED = 'rejected';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_CANCELED
     *
     * @var string
     */
    protected const STATUS_CANCELED = 'canceled';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::getApplicableForRequestStatusChangeMailNotificationStatuses()
     *
     * @var list<string>
     */
    protected const APPLICABLE_FOR_REQUEST_STATUS_CHANGE_MAIL_NOTIFICATION_STATUSES = [
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Communication\Plugin\Mail\MerchantRelationRequestStatusChangeMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE = 'merchant relation request status change';

    /**
     * @var \SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester
     */
    protected MerchantRelationRequestBusinessTester $tester;

    /**
     * @dataProvider testCallsMailFacadeWithMailTransferDataProvider
     *
     * @param string $status
     *
     * @return void
     */
    public function testCallsMailFacadeWithMailTransfer(string $status): void
    {
        // Assert
        $customerTransfer = (new CustomerTransfer())
            ->setFirstName('firstName')
            ->setLastName('lastName')
            ->setEmail('email');
        $merchantRelationRequestTransfer = (new MerchantRelationRequestTransfer())
            ->setCompanyUser((new CompanyUserTransfer())->setCustomer($customerTransfer))
            ->setStatus($status)
            ->setUuid('uuid');
        $merchantRelationRequestCollectionResponseTransfer = (new MerchantRelationRequestCollectionResponseTransfer())
            ->addMerchantRelationRequest($merchantRelationRequestTransfer);
        $mailFacadeMock = $this->createMailFacadeMock($merchantRelationRequestTransfer);

        // Arrange
        $this->tester->setDependency(MerchantRelationRequestDependencyProvider::FACADE_MAIL, $mailFacadeMock);
        $merchantRelationRequestFacade = $this->getMerchantRelationRequestFacade();

        // Act
        $merchantRelationRequestFacade->sendRequestStatusChangeMailNotification($merchantRelationRequestCollectionResponseTransfer);
    }

    /**
     * @dataProvider testDoesNotCallMailFacadeDataProvider
     *
     * @param string $status
     *
     * @return void
     */
    public function testDoesNotCallMailFacade(string $status): void
    {
        // Assert
        $customerTransfer = (new CustomerTransfer())
            ->setFirstName('firstName')
            ->setLastName('lastName')
            ->setEmail('email');
        $merchantRelationRequestTransfer = (new MerchantRelationRequestTransfer())
            ->setCompanyUser((new CompanyUserTransfer())->setCustomer($customerTransfer))
            ->setStatus($status)
            ->setUuid('uuid');
        $merchantRelationRequestCollectionResponseTransfer = (new MerchantRelationRequestCollectionResponseTransfer())
            ->addMerchantRelationRequest($merchantRelationRequestTransfer);
        $mailFacadeMock = $this->createMailFacadeMock($merchantRelationRequestTransfer, false);

        // Arrange
        $this->tester->setDependency(MerchantRelationRequestDependencyProvider::FACADE_MAIL, $mailFacadeMock);
        $merchantRelationRequestFacade = $this->getMerchantRelationRequestFacade();

        // Act
        $merchantRelationRequestFacade->sendRequestStatusChangeMailNotification($merchantRelationRequestCollectionResponseTransfer);
    }

    /**
     * @return array<string, list<string>>
     */
    public function testCallsMailFacadeWithMailTransferDataProvider(): array
    {
        return [
            'With status approved' => [static::STATUS_APPROVED],
            'With status rejected' => [static::STATUS_REJECTED],
        ];
    }

    /**
     * @return array<string, list<string>>
     */
    public function testDoesNotCallMailFacadeDataProvider(): array
    {
        return [
            'With status pending' => [static::STATUS_PENDING],
            'With status canceled' => [static::STATUS_CANCELED],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param bool $callMailFacade
     *
     * @return \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMailFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMailFacadeMock(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        bool $callMailFacade = true
    ): MerchantRelationRequestToMailFacadeInterface {
        $mailTransfer = (new MailTransfer())
            ->setCustomer($merchantRelationRequestTransfer->getCompanyUserOrFail()->getCustomerOrFail())
            ->setType(static::MAIL_TYPE)
            ->setMerchantRelationRequest($merchantRelationRequestTransfer)
            ->setMerchantRelationRequestLink('http://yves.de.spryker.local/company/merchant-relation-request/details/uuid');

        $mailFacadeMock = $this
            ->getMockBuilder(MerchantRelationRequestToMailFacadeInterface::class)
            ->getMock();

        $mailFacadeMock
            ->expects($callMailFacade ? $this->once() : $this->never())
            ->method('handleMail')
            ->with($mailTransfer);

        return $mailFacadeMock;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface
     */
    protected function getMerchantRelationRequestFacade(): MerchantRelationRequestFacadeInterface
    {
        $merchantRelationRequestConfigMock = $this->getMockBuilder(MerchantRelationRequestConfig::class)
            ->onlyMethods(['getYvesBaseUrl', 'getApplicableForRequestStatusChangeMailNotificationStatuses'])
            ->getMock();
        $merchantRelationRequestConfigMock->method('getYvesBaseUrl')->willReturn('http://yves.de.spryker.local');
        $merchantRelationRequestConfigMock->method('getApplicableForRequestStatusChangeMailNotificationStatuses')
            ->willReturn(static::APPLICABLE_FOR_REQUEST_STATUS_CHANGE_MAIL_NOTIFICATION_STATUSES);

        $merchantRelationRequestBusinessFactory = new MerchantRelationRequestBusinessFactory();
        $merchantRelationRequestBusinessFactory->setConfig($merchantRelationRequestConfigMock);

        return (new MerchantRelationRequestFacade())
            ->setFactory($merchantRelationRequestBusinessFactory);
    }
}
