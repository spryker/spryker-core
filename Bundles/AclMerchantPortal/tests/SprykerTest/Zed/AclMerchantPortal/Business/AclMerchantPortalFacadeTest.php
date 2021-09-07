<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTable;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryStore;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\Merchant\Persistence\SpyMerchantStore;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductAbstractStore;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Orm\Zed\Stock\Persistence\SpyStockStore;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\User\Persistence\SpyUser;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
use Spryker\Zed\AclMerchantPortal\Communication\Plugin\AclEntity\MerchantPortalAclEntityMetadataConfigExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclMerchantPortal
 * @group Business
 * @group Facade
 * @group AclMerchantPortalFacadeTest
 * Add your own group annotations below this line
 */
class AclMerchantPortalFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\AclMerchantPortal\Business\Writer\AclMerchantPortalWriter::ERROR_MESSAGE_MERCHANT_REFERENCE
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_REFERENCE = 'Merchant reference not found';

    /**
     * @uses \Spryker\Zed\AclMerchantPortal\Business\Writer\AclMerchantPortalWriter::ERROR_MESSAGE_MERCHANT_NAME
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_NAME = 'Merchant name not found';

    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE = 'testMerchantReference';
    /**
     * @var string
     */
    protected const MERCHANT_NAME = 'Test Merchant';
    /**
     * @var int
     */
    protected const ID_USER = 1;
    /**
     * @var string
     */
    protected const USER_FIRST_NAME = 'Fname';
    /**
     * @var string
     */
    protected const USER_LAST_NAME = 'Lname';

    /**
     * @var \SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->setDependency(
            AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_METADATA_COLLECTION_EXPANDER,
            [new MerchantPortalAclEntityMetadataConfigExpanderPlugin()]
        );
    }

    /**
     * @return void
     */
    public function testCreateMerchantAclDataFailsWithEmptyMerchantReference(): void
    {
        // Arrange
        $merchantTransfer = new MerchantTransfer();

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchantAclData($merchantTransfer);

        // Assert
        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
        $this->assertCount(1, $merchantResponseTransfer->getErrors());
        $this->assertSame(
            static::ERROR_MESSAGE_MERCHANT_REFERENCE,
            $merchantResponseTransfer->getErrors()->getIterator()->current()->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testCreateMerchantAclDataFailsWithEmptyMerchantName(): void
    {
        // Arrange
        $merchantTransfer = (new MerchantTransfer())->setMerchantReference(static::MERCHANT_REFERENCE);

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchantAclData($merchantTransfer);

        // Assert
        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
        $this->assertCount(1, $merchantResponseTransfer->getErrors());
        $this->assertSame(
            static::ERROR_MESSAGE_MERCHANT_NAME,
            $merchantResponseTransfer->getErrors()->getIterator()->current()->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testCreateMerchantAclDataSuccess(): void
    {
        // Arrange
        $this->tester->clearAllAclMerchantData();
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE, MerchantTransfer::NAME => static::MERCHANT_NAME]);

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchantAclData($merchantTransfer);

        // Assert
        $this->tester->assertAclMerchantData();
        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
        $this->assertCount(0, $merchantResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCreateMerchantUserAclDataSuccess(): void
    {
        // Arrange
        $this->tester->clearAllAclMerchantData();

        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE,
            MerchantTransfer::NAME => static::MERCHANT_NAME,
        ]);
        $userTransfer = $this->tester->haveUser([
            UserTransfer::FIRST_NAME => static::USER_FIRST_NAME,
            UserTransfer::LAST_NAME => static::USER_LAST_NAME,
        ]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantUserTransfer->setUser($userTransfer);

        // Act
        $this->tester->getFacade()->createMerchantUserAclData($merchantUserTransfer);

        // Assert
        $this->tester->assertAclMerchantUserData();
    }

    /**
     * @return void
     */
    public function testExpandAclEntityMetadataConfigSuccess(): void
    {
        // Act
        $aclEntityMetadataConfigTransfer = $this->tester->getAclEntityMetadataConfigTransfer();
        $aclEntityMetadataConfigTransfer = $this->tester->getFacade()->expandAclEntityMetadataConfig(
            $aclEntityMetadataConfigTransfer
        );

        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();

        // Assert
        $this->assertInstanceOf(AclEntityMetadataConfigTransfer::class, $aclEntityMetadataConfigTransfer);
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertSame(103, count($aclEntityMetadataCollectionTransfer->getCollection()));
        $this->assertArrayHasKey(SpyMerchantSalesOrderTotals::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyStateMachineItemState::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantSalesOrderItem::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpySalesOrderItem::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpySalesOrder::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantSalesOrder::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyAvailability::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyAvailabilityAbstract::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductImage::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductImageSetToProductImage::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductImageSet::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductLocalizedAttributes::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductAbstractLocalizedAttributes::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductCategory::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProduct::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyStockProduct::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductAbstract::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantProductAbstract::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyUser::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantUser::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchant::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantProfileAddress::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantProfile::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantStock::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantStore::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyMerchantCategory::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyPriceProductOffer::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductOfferStore::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductOfferValidity::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyCategoryClosureTable::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyCategoryNode::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyCategoryStore::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyStockStore::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductOfferStock::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyStock::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductAbstractStore::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyCategoryImage::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyCategoryImageSetToCategoryImage::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyCategoryImageSet::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductManagementAttributeValue::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductOffer::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyProductManagementAttribute::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyPriceProductDefault::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyPriceProductStore::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyTaxRate::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyGlossaryTranslation::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyRefund::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpySalesExpense::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpySalesOrderTotals::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyCustomer::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertArrayHasKey(SpyOmsProductReservationChangeVersion::class, $aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyMerchantSalesOrderTotals::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyMerchantSalesOrderItem::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpySalesOrderItem::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpySalesOrder::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyMerchantSalesOrder::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyAvailability::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyProduct::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyProductAbstract::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyMerchantProductAbstract::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyStockProduct::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyProductCategory::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyProductLocalizedAttributes::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyProductImageSetToProductImage::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyMerchantProfileAddress::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyMerchantProfile::class]->getParent());
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyMerchantStock::class]->getParent());
        $this->assertEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyStateMachineItemState::class]->getParent());
        $this->assertEmpty($aclEntityMetadataCollectionTransfer->getCollection()[SpyProductImage::class]->getParent());
    }
}
