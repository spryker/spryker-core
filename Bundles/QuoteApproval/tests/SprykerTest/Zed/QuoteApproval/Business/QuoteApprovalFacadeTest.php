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
use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Quote\QuoteApprovalExpanderPlugin;

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
    public function testCreateQuoteApprovalQuoteShouldBeSharedWithApproverOnly(): void
    {
        //Assign
        $this->prepareEnvForQuoteApprovalCreation();
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();

        //Act
        $quoteApprovalRepsponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $shareDeatailCollectionTransfer = $this->getShareDetailsByIdQuote(
            $quoteApprovalCreateRequestTransfer->getIdQuote()
        );

        $this->assertEquals(true, $quoteApprovalRepsponseTransfer->getIsSuccessful());
        $this->assertCount(1, $shareDeatailCollectionTransfer->getShareDetails());
        $this->assertEquals(
            $shareDeatailCollectionTransfer->getShareDetails()->offsetGet(0)->getIdCompanyUser(),
            $quoteApprovalCreateRequestTransfer->getIdCompanyUser()
        );
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalApprovalShouldBeCreated(): void
    {
        //Assign
        $this->prepareEnvForQuoteApprovalCreation();

        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();

        //Act
        $quoteApprovalRepsponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(true, $quoteApprovalRepsponseTransfer->getIsSuccessful());

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getIdQuote());

        $this->assertCount(1, $quoteTransfer->getApprovals());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfullWithApproverLimitLessThatQuoteGrantTotal(): void
    {
        //Assign
        $this->prepareEnvForQuoteApprovalCreation();

        $quoteTransfer = $this->createQuoteWithGrandTodal(10);
        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);
        $this->approverCanApproveUpToAmount(9, $quoteTransfer);

        //Act
        $quoteApprovalRepsponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteApprovalRepsponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfApproverDoesNotHavePermission(): void
    {
        //Assign
        $this->prepareEnvForQuoteApprovalCreation();

        $quoteTransfer = $this->createQuoteWithGrandTodal(10);
        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);

        //Act
        $quoteApprovalRepsponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteApprovalRepsponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfSentNotByQuoteOwner(): void
    {
        //Assign
        $this->prepareEnvForQuoteApprovalCreation();

        $quoteTransfer = $this->createQuoteWithGrandTodal(10);
        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);

        $this->approverCanApproveUpToAmount(11, $quoteTransfer);

        $notQuoteOwnerCustomerTransfer = $this->tester->haveCustomer();
        $quoteApprovalCreateRequestTransfer->setCustomerReference($notQuoteOwnerCustomerTransfer->getCustomerReference());

        //Act
        $quoteApprovalRepsponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteApprovalRepsponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfSentTwice(): void
    {
        //Assign
        $this->prepareEnvForQuoteApprovalCreation();

        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Act
        $quoteApprovalRepsponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteApprovalRepsponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testRemoveQuoteApprovalRemovesCartSharings(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $quoteApprovalRemoveRequestTransfer = $this->createValidQuoteApprovalRemoveRequestTransfer($quoteApprovalCreateRequestTransfer);

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

        //Assert
        $shareDeatailCollectionTransfer = $this->getShareDetailsByIdQuote(
            $quoteApprovalCreateRequestTransfer->getIdQuote()
        );

        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $shareDeatailCollectionTransfer->getShareDetails());
    }

    /**
     * @return void
     */
    public function testRemoveQuoteApprovalRemovesApprovalRequest(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();

        $quoteApprovalRemoveRequestTransfer = $this->createValidQuoteApprovalRemoveRequestTransfer(
            $quoteApprovalCreateRequestTransfer
        );

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

        //Assert
        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());

        $quoteApprovalTransfers = $this->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getIdQuote());

        $this->assertCount(0, $quoteApprovalTransfers);
    }

    /**
     * @return void
     */
    public function testRemoveQuoteApprovalNotSuccessfulIfSentNotByQuoteOwner(): void
    {
        //Assign
        $quoteApprovalRemoveRequestTransfer = $this->createValidQuoteApprovalRemoveRequestTransfer(
            $this->createValidQuoteApprovalCreateRequestTransfer()
        );

        $notQuoteOwnerCustomerTransfer = $this->tester->haveCustomer();

        $quoteApprovalRemoveRequestTransfer->setCustomerReference($notQuoteOwnerCustomerTransfer->getCustomerReference());

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

        //Assert
        $this->assertFalse($quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer
     */
    protected function createValidQuoteApprovalRemoveRequestTransfer(
        QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
    ): QuoteApprovalRemoveRequestTransfer {
        $quoteApproveRepsponseTransfer = $this->getFacade()
            ->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteApprovalTransfers = $this->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getIdQuote());

        $quoteApprovalRemoveRequestTransfer = new QuoteApprovalRemoveRequestTransfer();
        $quoteApprovalRemoveRequestTransfer->setCustomerReference($quoteApprovalCreateRequestTransfer->getCustomerReference());
        $quoteApprovalRemoveRequestTransfer->setIdQuoteApproval($quoteApprovalTransfers[0]->getIdQuoteApproval());

        return $quoteApprovalRemoveRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer
     */
    protected function createValidQuoteApprovalCreateRequestTransfer(): QuoteApprovalCreateRequestTransfer
    {
        $quoteTransfer = $this->createQuoteWithGrandTodal(10);

        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);
        $quoteApprovalCreateRequestTransfer->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());

        $this->approverCanApproveUpToAmount(11, $quoteTransfer);

        return $quoteApprovalCreateRequestTransfer;
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
     * @return \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer
     */
    protected function createQuoteApprovalCreateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteApprovalCreateRequestTransfer
    {
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();

        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $quoteApprovalCreateRequestTransfer = new QuoteApprovalCreateRequestTransfer();
        $quoteApprovalCreateRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteApprovalCreateRequestTransfer->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        return $quoteApprovalCreateRequestTransfer;
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
    protected function prepareEnvForQuoteApprovalCreation(): void
    {
        $this->setApproverPermissions(new PermissionCollectionTransfer());

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new PlaceOrderPermissionPlugin(),
            new ApproveQuotePermissionPlugin(),
        ]);

        $this->tester->setDependency(
            QuoteDependencyProvider::PLUGINS_QUOTE_EXPANDER,
            [
                new QuoteApprovalExpanderPlugin(),
            ]
        );
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteById(int $idQuote): ?QuoteTransfer
    {
        return $this->tester->getLocator()
            ->quote()
            ->facade()
            ->findQuoteById($idQuote)
            ->getQuoteTransfer();
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    protected function getShareDetailsByIdQuote(int $idQuote): ShareDetailCollectionTransfer
    {
        return $this->tester->getLocator()
            ->sharedCart()
            ->facade()
            ->getShareDetailsByIdQuote(
                (new QuoteTransfer())->setIdQuote($idQuote)
            );
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
