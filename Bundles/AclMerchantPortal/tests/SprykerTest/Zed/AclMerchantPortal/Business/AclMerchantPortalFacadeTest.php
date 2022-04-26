<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;
use Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalBusinessFactory;
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
     *
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_REFERENCE = 'Merchant reference not found';

    /**
     * @uses \Spryker\Zed\AclMerchantPortal\Business\Writer\AclMerchantPortalWriter::ERROR_MESSAGE_MERCHANT_NAME
     *
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
            [new MerchantPortalAclEntityMetadataConfigExpanderPlugin()],
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
            $merchantResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
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
            $merchantResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreateMerchantAclDataSuccess(): void
    {
        // Arrange
        $this->tester->clearAllAclMerchantData();
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE,
            MerchantTransfer::NAME => static::MERCHANT_NAME,
        ]);

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
            $aclEntityMetadataConfigTransfer,
        );

        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();

        // Assert
        $this->assertInstanceOf(AclEntityMetadataConfigTransfer::class, $aclEntityMetadataConfigTransfer);
        $this->assertNotEmpty($aclEntityMetadataCollectionTransfer->getCollection());
        $this->assertSame(108, count($aclEntityMetadataCollectionTransfer->getCollection()));
        $this->assertAclEntityMetadataConfigEntityWithoutParents($aclEntityMetadataCollectionTransfer);
        $this->assertAclEntityMetadataConfigHasEntities($aclEntityMetadataCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testCheckUserRoleFilterConditionForRootGroupAndProperRole(): void
    {
        //Arrange
        $groupTransfer = $this->tester->haveGroup(['name' => 'test_group', 'reference' => 'root_group_reference']);
        $userTransfer = $this->tester->haveUser();

        $aclFacade = $this->tester->getLocator()->acl()->facade();
        $aclMerchantPortalFacade = $this->tester->getFacade();

        $configMock = $this->createMock(AclMerchantPortalConfig::class);
        $configMock->method('getBackofficeAllowedAclGroupReferences')->willReturn(['root_group_reference']);
        $configMock->method('getRolesWithBackofficeAccess')->willReturn(['role_back_office_user']);

        $factory = new AclMerchantPortalBusinessFactory();
        $factory->setConfig($configMock);
        $aclMerchantPortalFacade->setFactory($factory);

        // Act
        $aclFacade->addUserToGroup($userTransfer->getIdUser(), $groupTransfer->getIdAclGroup());
        $boolCondition = $aclMerchantPortalFacade->checkUserRoleFilterCondition($userTransfer, 'role_back_office_user');

        // Assert
        $this->assertFalse($boolCondition);
    }

    /**
     * @return void
     */
    public function testCheckUserRoleFilterConditionForRootGroupAndWrongRole(): void
    {
        //Arrange
        $groupTransfer = $this->tester->haveGroup(['name' => 'test_group', 'reference' => 'root_group_reference']);
        $userTransfer = $this->tester->haveUser();
        $aclFacade = $this->tester->getLocator()->acl()->facade();

        // Act
        $aclFacade->addUserToGroup($userTransfer->getIdUser(), $groupTransfer->getIdAclGroup());
        $boolCondition = $this->tester->getFacade()->checkUserRoleFilterCondition($userTransfer, 'WRONG_ROLE');

        // Assert
        $this->assertTrue($boolCondition);
    }

    /**
     * @return void
     */
    public function testCheckUserRoleFilterConditionForNotRootGroupAndProperRole(): void
    {
        //Arrange
        $groupTransfer = $this->tester->haveGroup(['name' => 'test_group', 'reference' => 'not_root_groop']);
        $userTransfer = $this->tester->haveUser();
        $aclFacade = $this->tester->getLocator()->acl()->facade();

        // Act
        $aclFacade->addUserToGroup($userTransfer->getIdUser(), $groupTransfer->getIdAclGroup());
        $boolCondition = $this->tester->getFacade()->checkUserRoleFilterCondition($userTransfer, 'ROLE_BACK_OFFICE_USER');

        // Assert
        $this->assertTrue($boolCondition);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @return void
     */
    protected function assertAclEntityMetadataConfigEntityWithoutParents(AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer): void
    {
        $collection = $aclEntityMetadataCollectionTransfer->getCollection();
        $entityWithoutParents = [
            'Orm\Zed\Category\Persistence\SpyCategory',
            'Orm\Zed\Category\Persistence\SpyCategoryTemplate',
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlock',
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping',
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate',
            'Orm\Zed\Comment\Persistence\SpyCommentThread',
            'Orm\Zed\Country\Persistence\SpyCountry',
            'Orm\Zed\Currency\Persistence\SpyCurrency',
            'Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange',
            'Orm\Zed\Glossary\Persistence\SpyGlossaryKey',
            'Orm\Zed\Locale\Persistence\SpyLocale',
            'Orm\Zed\Merchant\Persistence\SpyMerchant',
            'Orm\Zed\Oms\Persistence\SpyOmsOrderItemState',
            'Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory',
            'Orm\Zed\Oms\Persistence\SpyOmsOrderProcess',
            'Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion',
            'Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock',
            'Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType',
            'Orm\Zed\PriceProduct\Persistence\SpyPriceProduct',
            'Orm\Zed\PriceProduct\Persistence\SpyPriceType',
            'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation',
            'Orm\Zed\ProductImage\Persistence\SpyProductImage',
            'Orm\Zed\ProductImage\Persistence\SpyProductImageSet',
            'Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup',
            'Orm\Zed\ProductOption\Persistence\SpyProductOptionValue',
            'Orm\Zed\Product\Persistence\SpyProductAttributeKey',
            'Orm\Zed\Shipment\Persistence\SpyShipmentCarrier',
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethod',
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice',
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineLock',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog',
            'Orm\Zed\Store\Persistence\SpyStore',
            'Orm\Zed\Tax\Persistence\SpyTaxSet',
            'Orm\Zed\Url\Persistence\SpyUrl',
            'Orm\Zed\Url\Persistence\SpyUrlRedirect',
            'Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword',
        ];

        foreach ($entityWithoutParents as $entityWithoutParent) {
            $this->assertEmpty($collection[$entityWithoutParent]->getParent());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @return void
     */
    protected function assertAclEntityMetadataConfigHasEntities(AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer): void
    {
        $collection = $aclEntityMetadataCollectionTransfer->getCollection();
        $entities = [
            'Orm\Zed\Product\Persistence\SpyProduct',
            'Orm\Zed\Store\Persistence\SpyStore',
            'Orm\Zed\ProductImage\Persistence\SpyProductImage',
            'Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes',
            'Orm\Zed\CategoryImage\Persistence\SpyCategoryImage',
            'Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage',
            'Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet',
            'Orm\Zed\Category\Persistence\SpyCategoryAttribute',
            'Orm\Zed\Category\Persistence\SpyCategoryClosureTable',
            'Orm\Zed\Category\Persistence\SpyCategoryNode',
            'Orm\Zed\Category\Persistence\SpyCategoryStore',
            'Orm\Zed\Category\Persistence\SpyCategory',
            'Orm\Zed\Category\Persistence\SpyCategoryTemplate',
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlock',
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping',
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate',
            'Orm\Zed\Comment\Persistence\SpyCommentThread',
            'Orm\Zed\Tax\Persistence\SpyTaxRate',
            'Orm\Zed\Country\Persistence\SpyCountry',
            'Orm\Zed\Currency\Persistence\SpyCurrency',
            'Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange',
            'Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation',
            'Orm\Zed\Glossary\Persistence\SpyGlossaryKey',
            'Orm\Zed\Locale\Persistence\SpyLocale',
            'Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory',
            'Orm\Zed\Availability\Persistence\SpyAvailability',
            'Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract',
            'Orm\Zed\ProductCategory\Persistence\SpyProductCategory',
            'Orm\Zed\Oms\Persistence\SpyOmsProductReservation',
            'Orm\Zed\ProductSearch\Persistence\SpyProductSearch',
            'Orm\Zed\ProductValidity\Persistence\SpyProductValidity',
            'Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes',
            'Orm\Zed\Product\Persistence\SpyProductAbstractStore',
            'Orm\Zed\Product\Persistence\SpyProductAbstract',
            'Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract',
            'Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress',
            'Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile',
            'Orm\Zed\Oms\Persistence\SpyOmsTransitionLog',
            'Orm\Zed\Sales\Persistence\SpySalesDiscount',
            'Orm\Zed\Customer\Persistence\SpyCustomer',
            'Orm\Zed\Payment\Persistence\SpySalesPayment',
            'Orm\Zed\Refund\Persistence\SpyRefund',
            'Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoice',
            'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold',
            'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTaxSet',
            'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType',
            'Orm\Zed\Sales\Persistence\SpySalesExpense',
            'Orm\Zed\Sales\Persistence\SpySalesOrderComment',
            'Orm\Zed\Sales\Persistence\SpySalesOrderTotals',
            'Orm\Zed\Sales\Persistence\SpySalesShipment',
            'Orm\Zed\Sales\Persistence\SpySalesOrder',
            'Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard',
            'Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata',
            'Orm\Zed\Sales\Persistence\SpySalesOrderItemOption',
            'Orm\Zed\Sales\Persistence\SpySalesOrderItem',
            'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem',
            'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals',
            'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder',
            'Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock',
            'Orm\Zed\Stock\Persistence\SpyStockProduct',
            'Orm\Zed\Stock\Persistence\SpyStockStore',
            'Orm\Zed\Stock\Persistence\SpyStock',
            'Orm\Zed\MerchantStock\Persistence\SpyMerchantStock',
            'Orm\Zed\User\Persistence\SpyUser',
            'Orm\Zed\MerchantUser\Persistence\SpyMerchantUser',
            'Orm\Zed\Merchant\Persistence\SpyMerchantStore',
            'Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation',
            'Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer',
            'Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity',
            'Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore',
            'Orm\Zed\ProductOffer\Persistence\SpyProductOffer',
            'Orm\Zed\Merchant\Persistence\SpyMerchant',
            'Orm\Zed\Oms\Persistence\SpyOmsOrderItemState',
            'Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory',
            'Orm\Zed\Oms\Persistence\SpyOmsOrderProcess',
            'Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion',
            'Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock',
            'Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType',
            'Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault',
            'Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore',
            'Orm\Zed\PriceProduct\Persistence\SpyPriceProduct',
            'Orm\Zed\PriceProduct\Persistence\SpyPriceType',
            'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation',
            'Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage',
            'Orm\Zed\ProductImage\Persistence\SpyProductImageSet',
            'Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup',
            'Orm\Zed\ProductOption\Persistence\SpyProductOptionValue',
            'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue',
            'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute',
            'Orm\Zed\Product\Persistence\SpyProductAttributeKey',
            'Orm\Zed\Shipment\Persistence\SpyShipmentCarrier',
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethod',
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice',
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineLock',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess',
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog',
            'Orm\Zed\Tax\Persistence\SpyTaxSet',
            'Orm\Zed\Url\Persistence\SpyUrl',
            'Orm\Zed\Url\Persistence\SpyUrlRedirect',
            'Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword',
        ];

        foreach ($entities as $entity) {
            $this->assertArrayHasKey($entity, $collection);
        }
    }
}
