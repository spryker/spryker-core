<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOption\Business\Model;

use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Base\SpyProductOptionConfigurationPresetQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageExclusionQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionType;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValue;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsage;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsage;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageConstraintQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageQuery;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;

/**
 * @group Business
 * @group Zed
 * @group ProdutOption
 * @group DataImportWriterTest
 *
 * @method ProductOptionFacade getFacade()
 */
class DataImportWriterTest extends AbstractFunctionalTest
{

    /**
     * @var ProductOptionFacade
     */
    private $facade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->facade = $this->getFacade();
    }

    public function testImportProductOptionType()
    {
        $this->facade->importProductOptionType('SHADE', ['en_GB' => 'Shade']);
        $this->facade->importProductOptionType('SHADE', ['en_GB' => 'Shade']);

        $result = SpyProductOptionTypeQuery::create()->findByImportKey('SHADE');

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');
        $this->assertEquals('SHADE', $result[0]->getImportKey());
        $this->assertEquals('Shade', $result[0]->getSpyProductOptionTypeTranslations()[0]->getName());
    }

    public function testImportProductOptionValue()
    {
        $optionType = (new SpyProductOptionType())->setImportKey('SHADE');
        $optionType->save();

        $this->facade->importProductOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '2.99');
        $this->facade->importProductOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '2.99');

        $result = SpyProductOptionTypeQuery::create()
            ->findByImportKey('SHADE');

        $this->assertEquals('SHADE', $result[0]->getImportKey());
        $this->assertEquals(1, $result->count());

        $optionValues = $result[0]->getSpyProductOptionValues();
        $this->assertEquals(1, $optionValues->count(), 'Failed assetting that method is idempotent');

        $this->assertEquals('VIOLET', $optionValues[0]->getImportKey());
        $this->assertEquals(299, $optionValues[0]->getSpyProductOptionValuePrice()->getPrice());
        $this->assertEquals('Violet', $optionValues[0]->getSpyProductOptionValueTranslations()[0]->getName());
    }

    public function testTouchUpdatedWhenUpdatingProductOptionType()
    {
        $product = $this->createConcreteProduct();
        $optionType = $this->createOptionTypeWithValue();
        $this->createProductOptionTypeUsage($product, $optionType);

        $this->facade->importProductOptionType('SHADE', ['en_GB' => 'Shade']);

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testTouchUpdatedWhenUpdatingProductOptionValue()
    {
        $product = $this->createConcreteProduct();
        $optionType = $this->createOptionTypeWithValue();
        $optionTypeUsage = $this->createProductOptionTypeUsage($product, $optionType);
        $optionValueUsage = (new SpyProductOptionValueUsage())
            ->setFkProductOptionTypeUsage($optionTypeUsage->getIdProductOptionTypeUsage())
            ->setFkProductOptionValue($optionType->getSpyProductOptionValues()[0]->getIdProductOptionValue());

        $optionValueUsage->save();

        $this->facade->importProductOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '2.99');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionTypeUsage()
    {
        $product = $this->createConcreteProduct();

        $optionType = (new SpyProductOptionType())->setImportKey('SHADE');
        $optionType->save();

        $this->facade->importProductOptionTypeUsage('ABC123', 'SHADE');
        $this->facade->importProductOptionTypeUsage('ABC123', 'SHADE');

        $result = SpyProductOptionTypeUsageQuery::create()
            ->filterByFkProductOptionType($optionType->getIdProductOptionType())
            ->filterByFkProduct($product->getIdProduct())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionValueUsage()
    {
        $product = $this->createConcreteProduct();
        $optionType = $this->createOptionTypeWithValue();
        $productOptionTypeUsage = $this->createProductOptionTypeUsage($product, $optionType);

        $this->facade->importProductOptionValueUsage($productOptionTypeUsage->getIdProductOptionTypeUsage(),  'VIOLET');
        $this->facade->importProductOptionValueUsage($productOptionTypeUsage->getIdProductOptionTypeUsage(),  'VIOLET');

        $result = SpyProductOptionValueUsageQuery::create()
            ->filterByFkProductOptionTypeUsage($productOptionTypeUsage->getIdProductOptionTypeUsage())
            ->filterByFkProductOptionValue($optionType->getSpyProductOptionValues()[0]->getIdProductOptionValue())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionTypeUsageExclusion()
    {
        $product = $this->createConcreteProduct();
        $optionShadeViolet = $this->createOptionTypeWithValue();
        $optionFittingClassic = $this->createOptionTypeWithValue('FITTING', 'CLASSIC');

        $productOptionFitting = $this->createProductOptionTypeUsage($product, $optionFittingClassic);
        $productOptionShade = $this->createProductOptionTypeUsage($product, $optionShadeViolet);

        $this->facade->importProductOptionTypeUsageExclusion($product->getSku(), 'SHADE', 'FITTING');
        $this->facade->importProductOptionTypeUsageExclusion($product->getSku(), 'SHADE', 'FITTING');

        $result = SpyProductOptionTypeUsageExclusionQuery::create()
            ->filterByFkProductOptionTypeUsageA($productOptionShade->getIdProductOptionTypeUsage())
            ->filterByFkProductOptionTypeUsageB($productOptionFitting->getIdProductOptionTypeUsage())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionValueUsageConstraint()
    {
        $product = $this->createConcreteProduct();

        $optionShadeViolet = $this->createOptionTypeWithValue();
        $optionFittingClassic = $this->createOptionTypeWithValue('FITTING', 'CLASSIC');

        $productOptionFitting = $this->createProductOptionTypeUsage($product, $optionFittingClassic);
        $productOptionShade = $this->createProductOptionTypeUsage($product, $optionShadeViolet);

        $idProductOptionValueUsageSmall = $this->facade->importProductOptionValueUsage($productOptionFitting->getIdProductOptionTypeUsage(),  'CLASSIC');
        $idProductOptionValueUsageViolet = $this->facade->importProductOptionValueUsage($productOptionShade->getIdProductOptionTypeUsage(),  'VIOLET');

        $this->facade->importProductOptionValueUsageConstraint($product->getSku(), $idProductOptionValueUsageSmall, 'VIOLET', 'NOT');
        $this->facade->importProductOptionValueUsageConstraint($product->getSku(), $idProductOptionValueUsageViolet, 'CLASSIC', 'NOT');

        $result = SpyProductOptionValueUsageConstraintQuery::create()
            ->filterByFkProductOptionValueUsageA([$idProductOptionValueUsageSmall, $idProductOptionValueUsageViolet])
            ->filterByFkProductOptionValueUsageB([$idProductOptionValueUsageSmall, $idProductOptionValueUsageViolet])
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionPresetConfiguration()
    {
        $product = $this->createConcreteProduct();
        $optionShade = $this->createOptionTypeWithValue();
        $optionFitting = $this->createOptionTypeWithValue('FITTING', 'CLASSIC');

        $productOptionShade = $this->createProductOptionTypeUsage($product, $optionShade);
        $productOptionValueUsageViolet = (new SpyProductOptionValueUsage())
            ->setSpyProductOptionValue($optionShade->getSpyProductOptionValues()[0]);
        $productOptionShade->addSpyProductOptionValueUsage($productOptionValueUsageViolet);
        $productOptionShade->save();

        $productOptionFitting = $this->createProductOptionTypeUsage($product, $optionFitting);
        $productOptionValueUsageSmall = (new SpyProductOptionValueUsage())
            ->setSpyProductOptionValue($optionFitting->getSpyProductOptionValues()[0]);
        $productOptionFitting->addSpyProductOptionValueUsage($productOptionValueUsageSmall);
        $productOptionFitting->save();

        $this->facade->importPresetConfiguration($product->getSku(), ['VIOLET', 'CLASSIC']);

        $result = SpyProductOptionConfigurationPresetQuery::create()->findByFkProduct($product->getIdProduct());
        $this->assertEquals(1, $result->count());
        $values = $result[0]->getSpyProductOptionConfigurationPresetValues();
        foreach ($values as $value) {
            $this->assertContains(
                $value->getFkProductOptionValueUsage(),
                [
                    $productOptionValueUsageSmall->getIdProductOptionValueUsage(),
                    $productOptionValueUsageViolet->getIdProductOptionValueUsage(),
                ]
            );
        }

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    private function createConcreteProduct()
    {
        $abstractProduct = (new SpyAbstractProduct())
            ->setSku('ABC123')
            ->setAttributes('{}')
        ;
        $abstractProduct->save();

        $product = (new SpyProduct())
            ->setSku('ABC123')
            ->setAttributes('{}')
            ->setIsActive(true)
            ->setSpyAbstractProduct($abstractProduct)
        ;

        $product->save();

        return $product;
    }

    private function createOptionTypeWithValue($typeKey = 'SHADE', $valueKey = 'VIOLET')
    {
        $optionValue = (new SpyProductOptionValue())->setImportKey($valueKey);
        $optionType = (new SpyProductOptionType())->setImportKey($typeKey)->addSpyProductOptionValue($optionValue);
        $optionType->save();

        return $optionType;
    }

    private function createProductOptionTypeUsage($product, $optionType)
    {
        $productOptionTypeUsage = (new SpyProductOptionTypeUsage())
            ->setSpyProduct($product)
            ->setSpyProductOptionType($optionType)
            ->setIsOptional(false);

        $productOptionTypeUsage->save();

        return $productOptionTypeUsage;
    }

    private function performAssertionOnTouchTable($idAbstractProduct)
    {
        $query = SpyTouchQuery::create()
            ->filterByItemType('abstract_product')
            ->limit(1)
            ->orderByIdTouch('desc')
            ->find();

        $this->assertEquals(1, $query->count());
        foreach ($query as $touchEntity) {
            $this->assertEquals($touchEntity->getItemId(), $idAbstractProduct);
        }
    }

}
