<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig as SharedQuoteApprovalConfig;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalBusinessFactory;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Quote\QuoteApprovalExpanderPlugin;
use Spryker\Zed\QuoteApproval\QuoteApprovalConfig;

/**
 * Auto-generated group annotations
 *
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
    protected const VALUE_GRAND_TOTAL = 10;

    /**
     * @var \SprykerTest\Zed\QuoteApproval\QuoteApprovalBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsQuoteReadyForCheckoutIsSuccessful(): void
    {
        // Arrange
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $isQuoteReadyForCheckout = $this->tester->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isQuoteReadyForCheckout);
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testIsQuoteReadyForCheckoutIsNotSuccessful(): void
    {
        // Arrange
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $quoteTransfer->setQuoteApprovals($this->tester->createQuoteApprovalTransfers([
            SharedQuoteApprovalConfig::STATUS_WAITING,
        ]));

        // Act
        $isQuoteReadyForCheckout = $this->tester->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isQuoteReadyForCheckout);
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testGetQuoteFieldsAllowedForSavingReturnsCorrectQuoteFields(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteWithGrandTotal(static::VALUE_GRAND_TOTAL);

        $requiredQuoteFields = [
            QuoteTransfer::BILLING_ADDRESS,
        ];

        // Act
        $quoteFieldsAllowedForSaving = $this->getQuoteApprovalFacadeMock($requiredQuoteFields)
            ->getQuoteFieldsAllowedForSaving($quoteTransfer);

        // Assert
        $this->assertCount(0, $quoteFieldsAllowedForSaving);
    }

    /**
     * @return void
     */
    public function testGetQuoteFieldsAllowedForSavingNotReturnsRequiredQuoteFields(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $quoteApprovalRequestTransfer = $this->createValidQuoteApprovalRequestTransfer($quoteApprovalCreateRequestTransfer);
        $requiredQuoteFields = [
            QuoteTransfer::BILLING_ADDRESS,
        ];

        // Act
        $this->getQuoteApprovalFacadeMock($requiredQuoteFields)->approveQuoteApproval($quoteApprovalRequestTransfer);
        $quoteFieldsAllowedForSaving = $this->getQuoteApprovalFacadeMock($requiredQuoteFields)
            ->getQuoteFieldsAllowedForSaving($this->findQuoteById($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote()));

        // Assert
        $this->assertSame($requiredQuoteFields, $quoteFieldsAllowedForSaving);
    }

    /**
     * @return void
     */
    public function testQuoteFieldsAllowedForSavingSaveShippingAddressFails(): void
    {
        // Arrange
        $this->prepareEnvForQuoteApprovalCreation();
        $addressTransfer = (new AddressBuilder())->build();
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $quoteApprovalCreateRequestTransfer->getQuote()->setShippingAddress($addressTransfer);

        // Act
        $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);
        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        // Assert
        $this->assertNotEquals($addressTransfer, $quoteTransfer->getShippingAddress());
    }

    /**
     * @return void
     */
    public function testApproveQuoteApprovalSuccess(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $quoteApprovalRequestTransfer = $this->createValidQuoteApprovalRequestTransfer($quoteApprovalCreateRequestTransfer);

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->approveQuoteApproval($quoteApprovalRequestTransfer);

        // Assert
        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDeclineQuoteApprovalSuccess(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $quoteApprovalRequestTransfer = $this->createValidQuoteApprovalRequestTransfer($quoteApprovalCreateRequestTransfer);

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->declineQuoteApproval($quoteApprovalRequestTransfer);

        // Assert
        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalQuoteShouldBeSharedWithApproverOnly(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        // Assert
        $shareDeatailCollectionTransfer = $this->getShareDetailsByIdQuote(
            $quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote()
        );

        $this->assertEquals(true, $quoteApprovalResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $shareDeatailCollectionTransfer->getShareDetails());
        $this->assertEquals(
            $quoteApprovalCreateRequestTransfer->getApproverCompanyUserId(),
            $shareDeatailCollectionTransfer->getShareDetails()->offsetGet(0)->getIdCompanyUser()
        );
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalApprovalShouldBeCreated(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        // Assert
        $this->assertEquals(true, $quoteApprovalResponseTransfer->getIsSuccessful());

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        $this->assertCount(1, $quoteTransfer->getQuoteApprovals());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfullWithApproverLimitLessThatQuoteGrantTotal(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteWithGrandTotal(static::VALUE_GRAND_TOTAL);
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->approverCanApproveUpToAmount(9, $quoteTransfer);

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        // Assert
        $this->assertEquals(false, $quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfApproverDoesNotHavePermission(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteWithGrandTotal(static::VALUE_GRAND_TOTAL);
        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);
        $quoteApprovalCreateRequestTransfer->setRequesterCompanyUserId($quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        // Assert
        $this->assertEquals(false, $quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfSentNotByQuoteOwner(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteWithGrandTotal(static::VALUE_GRAND_TOTAL);
        $quoteApprovalCreateRequestTransfer = $this->createQuoteApprovalCreateRequestTransfer($quoteTransfer);

        $this->approverCanApproveUpToAmount(11, $quoteTransfer);

        $notQuoteCompanyUserTransfer = $this->tester->createCompanyUser();

        $quoteApprovalCreateRequestTransfer->setRequesterCompanyUserId($notQuoteCompanyUserTransfer->getIdCompanyUser());

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        // Assert
        $this->assertEquals(false, $quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteApprovalNotSuccessfulIfSentTwice(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        // Assert
        $this->assertEquals(false, $quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testRemoveQuoteApprovalRemovesCartSharings(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $quoteApprovalRemoveRequestTransfer = $this->createValidQuoteApprovalRemoveRequestTransfer($quoteApprovalCreateRequestTransfer);

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

        // Assert
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
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();

        $quoteApprovalRemoveRequestTransfer = $this->createValidQuoteApprovalRemoveRequestTransfer(
            $quoteApprovalCreateRequestTransfer
        );

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

        // Assert
        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());

        $quoteApprovalTransfers = $this->tester->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        $this->assertCount(0, $quoteApprovalTransfers);
    }

    /**
     * @return void
     */
    public function testRemoveQuoteApprovalNotSuccessfulIfSentNotByQuoteOwner(): void
    {
        // Arrange
        $quoteApprovalRemoveRequestTransfer = $this->createValidQuoteApprovalRemoveRequestTransfer(
            $this->createValidQuoteApprovalCreateRequestTransfer()
        );

        $notQuoteCompanyUserTransfer = $this->tester->createCompanyUser();
        $quoteApprovalRemoveRequestTransfer->setRequesterCompanyUserId($notQuoteCompanyUserTransfer->getIdCompanyUser());

        // Act
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

        // Assert
        $this->assertFalse($quoteApprovalResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testSanitizeQuoteApprovalSanitizeAllApprovalsFromQuoteAndRemovesFromDatabase(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        // Act
        $quoteTransfer = $this->tester->getFacade()->sanitizeQuoteApproval($quoteTransfer);
        $quoteApprovalTransfers = $this->tester->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        // Assert
        $this->assertEmpty($quoteTransfer->getQuoteApprovals());
        $this->assertEmpty($quoteApprovalTransfers);
    }

    /**
     * @return void
     */
    public function testSanitizeQuoteApprovalSanitizeAllApprovalsFromQuoteWithoutRemoving(): void
    {
        // Arrange
        $quoteApprovalCreateRequestTransfer = $this->createValidQuoteApprovalCreateRequestTransfer();
        $this->tester->getFacade()->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());
        $quoteTransfer->setIdQuote(null);

        // Act
        $quoteTransfer = $this->tester->getFacade()->sanitizeQuoteApproval($quoteTransfer);
        $quoteApprovalTransfers = $this->tester->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

        // Assert
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
        $this->tester->getFacade()
            ->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteApprovalTransfers = $this->tester->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

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
        $this->tester->getFacade()
            ->createQuoteApproval($quoteApprovalCreateRequestTransfer);

        $quoteApprovalTransfers = $this->tester->getFacade()->getQuoteApprovalsByIdQuote($quoteApprovalCreateRequestTransfer->getQuote()->getIdQuote());

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

        $quoteTransfer = $this->createQuoteWithGrandTotal(static::VALUE_GRAND_TOTAL);

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
        $companyUserTransfer = $this->tester->createCompanyUser();

        $quoteApprovalCreateRequestTransfer = new QuoteApprovalRequestTransfer();
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

        $companyUserTransfer = $this->tester->createCompanyUser();
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

        $companyUserTransfer->setCustomer(null);

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
     * @param string[] $requiredQuoteFields
     *
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface
     */
    protected function getQuoteApprovalFacadeMock(array $requiredQuoteFields): QuoteApprovalFacadeInterface
    {
        $quoteApprovalConfigMock = $this->getMockBuilder(QuoteApprovalConfig::class)
            ->setMethods(['getRequiredQuoteFields', 'isPermissionCalculationIncludeShipment'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteApprovalConfigMock
            ->method('getRequiredQuoteFields')
            ->willReturn($requiredQuoteFields);

        /** @var \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacade $quoteApprovalFacade */
        $quoteApprovalFacade = $this->tester->getFacade();
        $quoteApprovalFacade->setFactory((new QuoteApprovalBusinessFactory())->setConfig($quoteApprovalConfigMock));

        return $quoteApprovalFacade;
    }
}
