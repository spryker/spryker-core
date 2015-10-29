<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductCartConnector\Business\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerFeature\Zed\ProductCartConnector\Business\ProductCartConnectorFacade;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Product\Persistence\SpyAbstractProduct;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyLocalizedProductAttributes;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group SprykerFeature
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group ProductCartPlugin
 */
class ProductCartPluginTest extends AbstractFunctionalTest
{

    const SKU_ABSTRACT_PRODUCT = 'Abstract product sku';

    const SKU_CONCRETE_PRODUCT = 'Concrete product sku';

    const TAX_SET_NAME = 'Sales Tax';

    const TAX_RATE_NAME = 'VAT';

    const TAX_RATE_PERCENTAGE = 10;

    const CONCRETE_PRODUCT_NAME = 'Concrete product name';

    /**
     * @var ProductCartConnectorFacade
     */
    private $productCartConnectorFacade;

    /**
     * @var LocaleFacade
     */
    private $localeFacade;

    /**
     * @var AutoCompletion
     */
    private $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();

        $this->localeFacade = $this->locator->locale()->facade();
        $this->productCartConnectorFacade = $this->getFacade();
    }


    public function testPluginExpandsCartItemWithExpectedProductData()
    {
        $localeName = Store::getInstance()->getCurrentLocale();
        $localeTransfer = $this->localeFacade->getLocale($localeName);

        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setRate(self::TAX_RATE_PERCENTAGE)
            ->setName(self::TAX_RATE_NAME);

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->addSpyTaxRate($taxRateEntity)
            ->setName(self::TAX_SET_NAME);

        $abstractProductEntity = new SpyAbstractProduct();
        $abstractProductEntity->setSpyTaxSet($taxSetEntity)
            ->setAttributes('')
            ->setSku(self::SKU_ABSTRACT_PRODUCT);

        $localizedAttributesEntity = new SpyLocalizedProductAttributes();
        $localizedAttributesEntity->setName(self::CONCRETE_PRODUCT_NAME)
            ->setAttributes('')
            ->setFkLocale($localeTransfer->getIdLocale());

        $concreteProductEntity = new SpyProduct();
        $concreteProductEntity->setSpyAbstractProduct($abstractProductEntity)
            ->setAttributes('')
            ->addSpyLocalizedProductAttributes($localizedAttributesEntity)
            ->setSku(self::SKU_CONCRETE_PRODUCT)
            ->save();

        $changeTransfer = new ChangeTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(self::SKU_CONCRETE_PRODUCT);
        $changeTransfer->addItem($itemTransfer);

        $this->productCartConnectorFacade->expandItems($changeTransfer);

        $expandedItemTransfer = $changeTransfer->getItems()[0];

        $this->assertEquals(self::SKU_ABSTRACT_PRODUCT, $expandedItemTransfer->getAbstractSku());
        $this->assertEquals(self::SKU_CONCRETE_PRODUCT, $expandedItemTransfer->getSku());
        $this->assertEquals($abstractProductEntity->getIdAbstractProduct(), $expandedItemTransfer->getIdAbstractProduct());
        $this->assertEquals($concreteProductEntity->getIdProduct(), $expandedItemTransfer->getId());
        $expandedTSetTransfer = $expandedItemTransfer->getTaxSet();
        $this->assertNotNull($expandedTSetTransfer);
        $this->assertEquals(self::TAX_SET_NAME, $expandedTSetTransfer->getName());
    }

}
