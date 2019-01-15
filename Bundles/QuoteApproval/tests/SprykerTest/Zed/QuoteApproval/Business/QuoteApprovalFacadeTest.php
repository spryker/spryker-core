<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\QuoteApproval\Communication\Plugin\UpdateApprovalsQuoteAfterSavePlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group QuoteApproval
 * @group Business
 * @group Facade
 * @group QuoteApprovalFacadeTest
 * Add your own group annotations below this line
 */
class QuoteApprovalFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\QuoteApproval\QuoteApprovalBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSendQuoteApprovalRequestShouldLockQuote(): void
    {
        //Assign
        $this->prepareEnvForApprovalRequestSending();

        $quoteApproveRequestTransfer = $this->createValidQuoteApproveRequest();

        //Act
        $quoteRepsponseTransfer = $this->getFacade()->sendQuoteApproveRequest($quoteApproveRequestTransfer);

        //Assert
        $this->assertEquals(true, $quoteRepsponseTransfer->getQuoteTransfer()->getIsLocked());
    }

    /**
     * @return void
     */
    public function testSendQuoteApprovalRequestQuoteShouldBeSharedWithApproverOnly()
    {
        //Assign
        $this->prepareEnvForApprovalRequestSending();

        $quoteApproveRequestTransfer = $this->createValidQuoteApproveRequest();

        //Act
        $quoteRepsponseTransfer = $this->getFacade()->sendQuoteApproveRequest($quoteApproveRequestTransfer);

        //Assert
        $sharedDetails = $quoteRepsponseTransfer->getQuoteTransfer()->getShareDetails();

        $this->assertEquals(true, $quoteRepsponseTransfer->getIsSuccessful());
        $this->assertCount(1, $sharedDetails);
        $this->assertEquals($sharedDetails[0]->getIdCompanyUser(), $quoteApproveRequestTransfer->getIdApprover());
    }

    /**
     * @return void
     */
    public function testSendQuoteApprovalRequestApprovalShouldBeCreated()
    {
        //Assign
        $this->prepareEnvForApprovalRequestSending();

        $quoteApproveRequestTransfer = $this->createValidQuoteApproveRequest();

        //Act
        $quoteRepsponseTransfer = $this->getFacade()->sendQuoteApproveRequest($quoteApproveRequestTransfer);

        //Assert
        $this->assertEquals(true, $quoteRepsponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteRepsponseTransfer->getQuoteTransfer()->getApprovals());
    }

    /**
     * @return void
     */
    public function testSendQuoteApprovalRequestNotSuccessfullWithApproverLimitLessThatQuoteGrantTotal(): void
    {
        //Assign
        $this->prepareEnvForApprovalRequestSending();

        $quoteTransfer = $this->createQuoteWithGrandTodal(10);
        $quoteApproveRequestTransfer = $this->createQuoteApprovalRequest($quoteTransfer);
        $this->approverCanApproveUpToAmount(9, $quoteTransfer);

        //Act
        $quoteRepsponseTransfer = $this->getFacade()->sendQuoteApproveRequest($quoteApproveRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteRepsponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteRepsponseTransfer->getQuoteTransfer()->getApprovals());
    }

    /**
     * @return void
     */
    public function testSendQuoteApprovalRequestNotSuccessfulIfApproverDoesNotHavePermission(): void
    {
        //Assign
        $this->prepareEnvForApprovalRequestSending();

        $quoteTransfer = $this->createQuoteWithGrandTodal(10);
        $quoteApproveRequestTransfer = $this->createQuoteApprovalRequest($quoteTransfer);

        //Act
        $quoteRepsponseTransfer = $this->getFacade()->sendQuoteApproveRequest($quoteApproveRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteRepsponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteRepsponseTransfer->getQuoteTransfer()->getApprovals());
    }

    /**
     * @return void
     */
    public function testSendQuoteApprovalRequestNotSuccessfulIfSentNotByQuoteOwner(): void
    {
        //Assign
        $this->prepareEnvForApprovalRequestSending();

        $quoteTransfer = $this->createQuoteWithGrandTodal(10);
        $quoteApproveRequestTransfer = $this->createQuoteApprovalRequest($quoteTransfer);
        $this->approverCanApproveUpToAmount(11, $quoteTransfer);
        $quoteTransfer->setCustomer(
            $this->tester->haveCustomer()
        );

        //Act
        $quoteRepsponseTransfer = $this->getFacade()->sendQuoteApproveRequest($quoteApproveRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteRepsponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteRepsponseTransfer->getQuoteTransfer()->getApprovals());
    }

    /**
     * @return void
     */
    public function testSendQuoteApprovalRequestNotSuccessfulIfQuoteNotInCorrectStatus(): void
    {
        //Assign
        $this->prepareEnvForApprovalRequestSending();

        $validRequest = $this->createValidQuoteApproveRequest();

        $quoteTransfer = $validRequest->getQuote();
        $quoteTransfer->addApproval((new QuoteApprovalTransfer())->setStatus(QuoteApprovalConfig::STATUS_WAITING));

        $notValidRequest = $validRequest->setQuote($quoteTransfer);

        //Act
        $quoteRepsponseTransfer = $this->getFacade()->sendQuoteApproveRequest($notValidRequest);

        //Assert
        $this->assertEquals(false, $quoteRepsponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCancelQuoteApprovalRequestUnlocksQuote(): void
    {
        //Assign
        $quoteApprovalCancelRequestTransfer = $this->createValidCancelQuoteApprovalRequest();

        //Act
        $quoteResponseTransfer = $this->getFacade()->cancelQuoteApprovalRequest($quoteApprovalCancelRequestTransfer);

        //Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertFalse($quoteResponseTransfer->getQuoteTransfer()->getIsLocked());
    }

    /**
     * @return void
     */
    public function testCancelQuoteApprovalRequestRemovesCartSharings(): void
    {
        //Assign
        $quoteApprovalCancelRequestTransfer = $this->createValidCancelQuoteApprovalRequest();

        //Act
        $quoteResponseTransfer = $this->getFacade()->cancelQuoteApprovalRequest($quoteApprovalCancelRequestTransfer);

        //Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $quoteResponseTransfer->getQuoteTransfer()->getShareDetails());
    }

    /**
     * @return void
     */
    public function testCancelQuoteApprovalRequestRemovesApprovalRequest(): void
    {
        //Assign
        $quoteApprovalCancelRequestTransfer = $this->createValidCancelQuoteApprovalRequest();

        //Act
        $quoteResponseTransfer = $this->getFacade()->cancelQuoteApprovalRequest($quoteApprovalCancelRequestTransfer);

        //Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());

        $approvalIdsLeft = [];

        foreach ($quoteResponseTransfer->getQuoteTransfer()->getApprovals() as $approval) {
            $approvalIdsLeft = $approval->getIdQuoteApproval();
        }

        $this->assertNotContains($quoteApprovalCancelRequestTransfer->getIdQuoteApproval(), $approvalIdsLeft);
    }

    /**
     * @return void
     */
    public function testCancelQuoteApprovalRequestNotSuccessfulIfSentNotByQuoteOwner(): void
    {
        //Assign
        $validCancelRequest = $this->createValidCancelQuoteApprovalRequest();
        $invalidCancelRequest = $validCancelRequest->setCustomer($this->tester->haveCustomer());

        //Act
        $quoteResponseTransfer = $this->getFacade()->cancelQuoteApprovalRequest($invalidCancelRequest);

        //Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer
     */
    protected function createValidCancelQuoteApprovalRequest(): QuoteApprovalCancelRequestTransfer
    {
        $this->tester->setDependency(QuoteDependencyProvider::PLUGINS_QUOTE_UPDATE_AFTER, [
            new UpdateApprovalsQuoteAfterSavePlugin(),
        ]);

        $quoteApproveRepsponseTransfer = $this->getFacade()
            ->sendQuoteApproveRequest($this->createValidQuoteApproveRequest());

        $quoteTransfer = $quoteApproveRepsponseTransfer->getQuoteTransfer();
        $approvals = $this->getFacade()->getQuoteApprovalsByIdQuote($quoteTransfer->getIdQuote());
        $idQuoteApproval = $approvals[0]->getIdQuoteApproval();

        $quoteApprovalCancelRequestTransfer = new QuoteApprovalCancelRequestTransfer();
        $quoteApprovalCancelRequestTransfer->setQuote($quoteTransfer);
        $quoteApprovalCancelRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteApprovalCancelRequestTransfer->setIdQuoteApproval($idQuoteApproval);

        return $quoteApprovalCancelRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteApproveRequestTransfer
     */
    protected function createValidQuoteApproveRequest(): QuoteApproveRequestTransfer
    {
        $quoteTransfer = $this->createQuoteWithGrandTodal(10);

        $quoteApproveRequestTransfer = $this->createQuoteApprovalRequest($quoteTransfer);
        $quoteApproveRequestTransfer->setCustomer($quoteTransfer->getCustomer());

        $this->approverCanApproveUpToAmount(11, $quoteTransfer);

        return $quoteApproveRequestTransfer;
    }

    /**
     * @param int $amount
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function approverCanApproveUpToAmount(int $amount, QuoteTransfer $quoteTransfer): void
    {
        $this->addApproveQuotePermission([
            ApproveQuotePermissionPlugin::FIELD_STORE_MULTI_CURRENCY => [
                $quoteTransfer->getStore()->getName() => [
                    $quoteTransfer->getCurrency()->getCode() => $amount,
                ],
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApproveRequestTransfer
     */
    protected function createQuoteApprovalRequest(QuoteTransfer $quoteTransfer): QuoteApproveRequestTransfer
    {
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();

        $approverCompanyUser = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $quoteApproveRequestTransfer = new QuoteApproveRequestTransfer();
        $quoteApproveRequestTransfer->setQuote($quoteTransfer);
        $quoteApproveRequestTransfer->setIdApprover($approverCompanyUser->getIdCompanyUser());
        $quoteApproveRequestTransfer->setCustomer($quoteTransfer->getCustomer());

        return $quoteApproveRequestTransfer;
    }

    /**
     * @param array $configuration
     *
     * @return void
     */
    protected function addApproveQuotePermission(array $configuration): void
    {
        $placeOrderPermission = new PermissionTransfer();

        $placeOrderPermission->setKey(ApproveQuotePermissionPlugin::KEY);
        $placeOrderPermission->setConfiguration($configuration);

        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        $permissionCollectionTransfer->addPermission($placeOrderPermission);

        $this->setApproverPermissions($permissionCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     *
     * @return void
     */
    protected function setApproverPermissions(
        PermissionCollectionTransfer $permissionCollectionTransfer
    ): void {
        $permissionStoragePlugin = $this->createMock(PermissionStoragePluginInterface::class);
        $permissionStoragePlugin->method('getPermissionCollection')
            ->willReturn($permissionCollectionTransfer);

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            $permissionStoragePlugin,
        ]);
    }

    /**
     * @param int $limitInCents
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithGrandTodal(int $limitInCents): QuoteTransfer
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal($limitInCents);

        $quoteTransfer = $this->tester->havePersistentQuote(
            [
                QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
                QuoteTransfer::TOTALS => $totalsTransfer,
            ]
        );

        return $quoteTransfer;
    }

    /**
     * @return void
     */
    protected function prepareEnvForApprovalRequestSending(): void
    {
        $this->setApproverPermissions(new PermissionCollectionTransfer());

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new PlaceOrderPermissionPlugin(),
            new ApproveQuotePermissionPlugin(),
        ]);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface
     */
    protected function getFacade()
    {
        /**
         * @var \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface
         */
        $facade = $this->tester->getFacade();

        return $facade;
    }
}
