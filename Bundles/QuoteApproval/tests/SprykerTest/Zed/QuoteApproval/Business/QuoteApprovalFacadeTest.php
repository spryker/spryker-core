<?php

//ini_set('memory_limit', '512M');

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalBusinessFactory;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacade;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Quote\QuoteApprovalExpanderPlugin;
use Spryker\Zed\QuoteApproval\QuoteApprovalConfig;

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
    public function testQuoteFieldsAllowedForSaving()
    {
        //Assign
        $quoteTransfer = $this->createQuoteWithGrandTotal(10);

        //Act
        $QuoteFieldsAllowedForSaving = $this->getFacadeMock()
            ->getQuoteFieldsAllowedForSaving($quoteTransfer);

        //Assert
        $this->assertEquals($QuoteFieldsAllowedForSaving, $this->getRequiredQuoteFields());
    }

    /**
     * @return void
     */
    public function testApproveQuoteApprovalSuccess(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $quoteApprovalRequestTransfer = $this->createValidQuoteApprovalRequestTransfer($quoteApprovalCreateRequestTransfer);

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->approveQuoteApproval($quoteApprovalRequestTransfer);

        //Assert
        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDeclineQuoteApprovalSuccess(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $quoteApprovalRequestTransfer = $this->createValidQuoteApprovalRequestTransfer($quoteApprovalCreateRequestTransfer);

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->declineQuoteApproval($quoteApprovalRequestTransfer);

        //Assert
        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalQuoteShouldBeSharedWithApproverOnly(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $shareDeatailCollectionTransfer = $this->getShareDetailsByIdQuote(
            $quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote()
        );

        $this->assertEquals(true, $quoteApprovalResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $shareDeatailCollectionTransfer->getShareDetails());
        $this->assertEquals(
            $shareDeatailCollectionTransfer->getShareDetails()->offsetGet(0)->getIdCompanyUser(),
            $quoteApprovalCreateRequestTransfer->getApproverCompanyUserId()
        );
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalApprovalShouldBeCreated(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(true, $quoteApprovalResponseTransfer->getIsSuccessful());

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        $this->assertCount(1, $quoteTransfer->getQuoteApprovals());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfullWithApproverLimitLessThatQuoteGrantTotal(): void
    {
        //Assign
        $quoteTransfer = $this->createQuoteWithGrandTotal(10);
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->approverCanApproveUpToAmount(9, $quoteTransfer);

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfApproverDoesNotHavePermission(): void
    {
        //Assign
        $quoteTransfer = $this->createQuoteWithGrandTotal(10);
        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);
        $quoteApprovalCreateRequestTransfer->setRequesterCompanyUserId($quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfSentNotByQuoteOwner(): void
    {
        //Assign
        $quoteTransfer = $this->createQuoteWithGrandTotal(10);
        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);

        $this->approverCanApproveUpToAmount(11, $quoteTransfer);

        $notQuoteCompanyUserTransfer = $this->haveCompanyUser();

        $quoteApprovalCreateRequestTransfer->setRequesterCompanyUserId($notQuoteCompanyUserTransfer->getIdCompanyUser());

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfSentTwice(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        //Assert
        $this->assertEquals(false, $quoteApprovalResponseTransfer->getIsSuccessful());
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
            $quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote()
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

        $quoteApprovalTransfers = $this->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

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

        $notQuoteCompanyUserTransfer = $this->haveCompanyUser();
        $quoteApprovalRemoveRequestTransfer->setRequesterCompanyUserId($notQuoteCompanyUserTransfer->getIdCompanyUser());

        //Act
        $quoteApprovalResponseTransfer = $this->getFacade()->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

        //Assert
        $this->assertFalse($quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testSanitizeQuoteApprovalSanitizeAllApprovalsFromQuoteAndRemovesFromDatabase(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        //Act
        $quoteTransfer = $this->getFacade()->sanitizeQuoteApproval($quoteTransfer);
        $quoteApprovalTransfers = $this->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        //Assert
        $this->assertEmpty($quoteTransfer->getQuoteApprovals());
        $this->assertEmpty($quoteApprovalTransfers);
    }

    /**
     * @return void
     */
    public function testSanitizeQuoteApprovalSanitizeAllApprovalsFromQuoteWithoutRemoving(): void
    {
        //Assign
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());
        $quoteTransfer->setIdQuote(null);

        //Act
        $quoteTransfer = $this->getFacade()->sanitizeQuoteApproval($quoteTransfer);
        $quoteApprovalTransfers = $this->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        //Assert
        $this->assertEmpty($quoteTransfer->getQuoteApprovals());
        $this->assertCount(1, $quoteApprovalTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestTransfer
     */
    protected function createValidQuoteApprovalRemoveRequestTransfer(
        QuoteApprovalRequestTransfer $quoteApprovalCreateRequestTransfer
    ): QuoteApprovalRequestTransfer {
        $this->getFacade()
            ->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteApprovalTransfers = $this->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        $quoteApprovalRemoveRequestTransfer = new QuoteApprovalRequestTransfer();
        $quoteApprovalRemoveRequestTransfer->setRequesterCompanyUserId($quoteApprovalCreateRequestTransfer->getRequesterCompanyUserId());
        $quoteApprovalRemoveRequestTransfer->setIdQuoteApproval($quoteApprovalTransfers[0]->getIdQuoteApproval());

        return $quoteApprovalRemoveRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestTransfer
     */
    protected function createValidQuoteApprovalRequestTransfer(
        QuoteApprovalRequestTransfer $quoteApprovalCreateRequestTransfer
    ): QuoteApprovalRequestTransfer {
        $this->getFacade()
            ->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteApprovalTransfers = $this->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        $quoteApprovalRequestTransfer = new QuoteApprovalRequestTransfer();
        $quoteApprovalRequestTransfer->setApproverCompanyUserId($quoteApprovalCreateRequestTransfer->getRequesterCompanyUserId());
        $quoteApprovalRequestTransfer->setIdQuoteApproval($quoteApprovalTransfers[0]->getIdQuoteApproval());

        return $quoteApprovalRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestTransfer
     */
    protected function createValidQuoteApprovalCreateRequestTransfer(): QuoteApprovalRequestTransfer
    {
        $this->prepareEnvForQuoteApprovalCreation();

        $quoteTransfer = $this->createQuoteWithGrandTotal(10);

        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);
        $quoteApprovalCreateRequestTransfer->setRequesterCompanyUserId($quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());

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
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestTransfer
     */
    protected function createQuoteApprovalCreateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteApprovalRequestTransfer
    {
        $this->prepareEnvForQuoteApprovalCreation();
        $companyUserTransfer = $this->haveCompanyUser();

        $quoteApprovalCreateRequestTransfer = new QuoteApprovalRequestTransfer();
        $quoteApprovalCreateRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteApprovalCreateRequestTransfer->setQuote($quoteTransfer);
        $quoteApprovalCreateRequestTransfer->setApproverCompanyUserId($companyUserTransfer->getIdCompanyUser());

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
    protected function createQuoteWithGrandTotal(int $limitInCents): QuoteTransfer
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal($limitInCents);

        $companyUserTransfer = $this->haveCompanyUser();
        $concreteProductTransfer = $this->tester->haveProduct();

        $quoteTransfer = $this->tester->havePersistentQuote(
            [
                QuoteTransfer::CUSTOMER => $companyUserTransfer->getCustomer(),
                QuoteTransfer::TOTALS => $totalsTransfer,
                QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
                QuoteTransfer::ITEMS => [
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::ID => $concreteProductTransfer->getIdProductConcrete(),
                        ItemTransfer::SKU => $concreteProductTransfer->getSku(),
                    ],
                ],
            ]
        );

        $quoteTransfer->getCustomer()->setCompanyUserTransfer(
            $companyUserTransfer
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

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function haveCompanyUser(): CompanyUserTransfer
    {
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();

        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        return $companyUserTransfer;
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface
     */
    protected function getFacadeMock()
    {
        $facade = new QuoteApprovalFacade();
        $facade->setFactory($this->getFactoryMock());

        return $facade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFactoryMock()
    {
        $factoryMock = $this->getMockBuilder(QuoteApprovalBusinessFactory::class)
            ->setMethods(['getConfig'])
            ->getMock();

        $factoryMock->method('getConfig')
            ->willReturn($this->getConfigMock());

        return $factoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getConfigMock()
    {
        $configMock = $this->getMockBuilder(QuoteApprovalConfig::class)
            ->setMethods(['getRequiredQuoteFields'])
            ->getMock();

        $configMock->method('getRequiredQuoteFields')
            ->willReturn($this->getRequiredQuoteFields());

        return $configMock;
    }

    /**
     * @return string[]
     */
    protected function getRequiredQuoteFields(): array
    {
        return [
            QuoteTransfer::BILLING_ADDRESS,
            QuoteTransfer::SHIPPING_ADDRESS,
            QuoteTransfer::PAYMENTS,
            QuoteTransfer::SHIPMENT,
        ];
    }
}
