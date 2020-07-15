<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferValidity\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferValidity
 * @group Business
 * @group Facade
 * @group ProductOfferValidityFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferValidityFacadeTest extends Unit
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @var \SprykerTest\Zed\ProductOfferValidity\ProductOfferValidityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->truncateProductOfferValidities();
        $this->tester->ensureProductOfferValidityTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getDataCleanupHelper()->_addCleanup(function (): void {
            $this->tester->ensureProductOfferValidityTableIsEmpty();
        });
    }

    /**
     * @return void
     */
    public function testUpdateProductOfferStatusByValidityDate(): void
    {
        // Arrange
        $merchant = $this->tester->haveMerchant();

        $productOfferValid = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchant->getIdMerchant(),
            ProductOfferTransfer::IS_ACTIVE => false,
        ]);

        $productOfferInvalid = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchant->getIdMerchant(),
            ProductOfferTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->haveProductOfferValidity([
            ProductOfferValidityTransfer::ID_PRODUCT_OFFER => $productOfferValid->getIdProductOffer(),
            ProductOfferValidityTransfer::VALID_FROM => (new DateTime('yesterday'))->format('Y-m-d H:i:s'),
            ProductOfferValidityTransfer::VALID_TO => (new DateTime('tomorrow'))->format('Y-m-d H:i:s'),
        ]);

        $this->tester->haveProductOfferValidity([
            ProductOfferValidityTransfer::ID_PRODUCT_OFFER => $productOfferInvalid->getIdProductOffer(),
            ProductOfferValidityTransfer::VALID_FROM => (new DateTime('yesterday'))->format('Y-m-d H:i:s'),
            ProductOfferValidityTransfer::VALID_TO => (new DateTime('yesterday'))->format('Y-m-d H:i:s'),
        ]);

        // Act
        $this->tester->getFacade()->updateProductOfferStatusByValidityDate();

        $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setIdProductOffer($productOfferValid->getIdProductOffer());
        $productOfferValid = $this->getLocator()->productOffer()->facade()->findOne($productOfferCriteriaFilterTransfer);

        $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setIdProductOffer($productOfferInvalid->getIdProductOffer());
        $productOfferInvalid = $this->getLocator()->productOffer()->facade()->findOne($productOfferCriteriaFilterTransfer);

        // Assert
        $this->assertTrue($productOfferValid->getIsActive());
        $this->assertFalse($productOfferInvalid->getIsActive());
    }

    /**
     * @return void
     */
    public function testCreatePersistsNewEntityToDatabase(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferValidityTransfer = (new ProductOfferValidityTransfer())
            ->setIdProductOffer($productOfferTransfer->getIdProductOffer())
            ->setValidFrom((new DateTime())->format('Y-m-d H:i:s'))
            ->setValidTo((new DateTime('+1 days'))->format('Y-m-d H:i:s'));

        // Act
        $this->tester->getFacade()->create($productOfferValidityTransfer);
        $productOfferValidityTransferFromDb = $this->tester->getProductOfferValidityRepository()
            ->findProductOfferValidityByIdProductOffer($productOfferTransfer->getIdProductOffer());

        // Assert
        $this->assertEquals($productOfferValidityTransfer, $productOfferValidityTransferFromDb);
    }

    /**
     * @return void
     */
    public function testUpdateUpdatesProductOfferValidity(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferValidityTransfer = $this->tester->haveProductOfferValidity([
            ProductOfferValidityTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
        ]);
        $productOfferValidityTransfer->setValidFrom((new DateTime('+1 days'))->format('Y-m-d H:i:s'));
        $productOfferValidityTransfer->setValidTo((new DateTime('+2 days'))->format('Y-m-d H:i:s'));

        // Act
        $this->tester->getFacade()->update($productOfferValidityTransfer);
        $productOfferValidityTransferFromDb = $this->tester->getProductOfferValidityRepository()
            ->findProductOfferValidityByIdProductOffer($productOfferTransfer->getIdProductOffer());

        // Assert
        $this->assertEquals($productOfferValidityTransfer, $productOfferValidityTransferFromDb);
    }

    /**
     * @return void
     */
    public function testExpandProductOfferWithProductOfferValidityExpandsProductOffer(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferValidity([
            ProductOfferValidityTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
        ]);

        // Act
        $productOfferValidityTransfer = $this->tester->getFacade()->expandProductOfferWithProductOfferValidity(
            $productOfferTransfer
        )->getProductOfferValidity();
        $productOfferValidityTransferFromDb = $this->tester->getProductOfferValidityRepository()
            ->findProductOfferValidityByIdProductOffer($productOfferTransfer->getIdProductOffer());

        // Assert
        $this->assertEquals($productOfferValidityTransfer->toArray(), $productOfferValidityTransferFromDb->toArray());
    }
}
