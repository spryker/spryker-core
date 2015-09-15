<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOption\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Base\SpyProductOptionConfigurationPresetQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageExclusionQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionType;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValue;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsage;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsage;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageConstraintQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageQuery;

/**
 * @group Business
 * @group Zed
 * @group ProductOption
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
        $this->facade->flushBuffer();

        $result = SpyProductOptionTypeQuery::create()->findByImportKey('SHADE');

        $this->assertEquals(1, $result->count(), 'Failed asserting that method is idempotent');
        $this->assertEquals('SHADE', $result[0]->getImportKey());
        $this->assertEquals('Shade', $result[0]->getSpyProductOptionTypeTranslations()[0]->getName());
    }

    public function testImportProductOptionValue()
    {
        $optionType = (new SpyProductOptionType())->setImportKey('SHADE');
        $optionType->save();

        $this->facade->importProductOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '299');
        $this->facade->importProductOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '299');
        $this->facade->flushBuffer();

        $result = SpyProductOptionTypeQuery::create()
            ->findByImportKey('SHADE');

        $this->assertEquals('SHADE', $result[0]->getImportKey());
        $this->assertEquals(1, $result->count());

        $optionValues = $result[0]->getSpyProductOptionValues();
        $this->assertEquals(1, $optionValues->count(), 'Failed asserting that method is idempotent');

        $this->assertEquals('VIOLET', $optionValues[0]->getImportKey());
        $this->assertEquals(299, $optionValues[0]->getSpyProductOptionValuePrice()->getPrice());
        $this->assertEquals('Violet', $optionValues[0]->getSpyProductOptionValueTranslations()[0]->getName());
    }

    public function testTouchUpdatedWhenUpdatingProductOptionType()
    {
        $product = $this->createConcreteProduct();
        $optionType = $this->createOptionTypeWithValue('SHADE', 'VIOLET');
        $this->createProductOptionTypeUsage($product, $optionType);

        $this->facade->importProductOptionType('SHADE', ['en_GB' => 'Shade']);
        $this->facade->flushBuffer();

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testTouchUpdatedWhenUpdatingProductOptionValue()
    {
        $product = $this->createConcreteProduct();
        $optionType = $this->createOptionTypeWithValue('SHADE', 'VIOLET');
        $optionTypeUsage = $this->createProductOptionTypeUsage($product, $optionType);
        $optionValueUsage = (new SpyProductOptionValueUsage())
            ->setFkProductOptionTypeUsage($optionTypeUsage->getIdProductOptionTypeUsage())
            ->setFkProductOptionValue($optionType->getSpyProductOptionValues()[0]->getIdProductOptionValue());

        $optionValueUsage->save();

        $this->facade->importProductOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '2.99');
        $this->facade->flushBuffer();

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionTypeUsage()
    {
        $product = $this->createConcreteProduct();

        $optionType = (new SpyProductOptionType())->setImportKey('SHADE');
        $optionType->save();

        $this->facade->importProductOptionTypeUsage('ABC123', 'SHADE');
        $this->facade->importProductOptionTypeUsage('ABC123', 'SHADE');
        $this->facade->flushBuffer();

        $result = SpyProductOptionTypeUsageQuery::create()
            ->filterByFkProductOptionType($optionType->getIdProductOptionType())
            ->filterByFkProduct($product->getIdProduct())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed asserting that method is idempotent');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionValueUsage()
    {
        $product = $this->createConcreteProduct();
        $optionType = $this->createOptionTypeWithValue('SHADE', 'VIOLET');
        $productOptionTypeUsage = $this->createProductOptionTypeUsage($product, $optionType);

        $this->facade->importProductOptionValueUsage($productOptionTypeUsage->getIdProductOptionTypeUsage(),  'VIOLET');
        $this->facade->importProductOptionValueUsage($productOptionTypeUsage->getIdProductOptionTypeUsage(),  'VIOLET');
        $this->facade->flushBuffer();

        $result = SpyProductOptionValueUsageQuery::create()
            ->filterByFkProductOptionTypeUsage($productOptionTypeUsage->getIdProductOptionTypeUsage())
            ->filterByFkProductOptionValue($optionType->getSpyProductOptionValues()[0]->getIdProductOptionValue())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed asserting that method is idempotent');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionTypeUsageExclusion()
    {
        $product = $this->createConcreteProduct();
        $optionShadeViolet = $this->createOptionTypeWithValue('SHADE', 'VIOLET');
        $optionFittingClassic = $this->createOptionTypeWithValue('FITTING', 'CLASSIC');

        $productOptionFitting = $this->createProductOptionTypeUsage($product, $optionFittingClassic);
        $productOptionShade = $this->createProductOptionTypeUsage($product, $optionShadeViolet);

        $this->facade->importProductOptionTypeUsageExclusion($product->getSku(), 'SHADE', 'FITTING');
        $this->facade->importProductOptionTypeUsageExclusion($product->getSku(), 'SHADE', 'FITTING');
        $this->facade->flushBuffer();

        $result = SpyProductOptionTypeUsageExclusionQuery::create()
            ->filterByFkProductOptionTypeUsageA($productOptionShade->getIdProductOptionTypeUsage())
            ->filterByFkProductOptionTypeUsageB($productOptionFitting->getIdProductOptionTypeUsage())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed asserting that method is idempotent');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionValueUsageConstraint()
    {
        $product = $this->createConcreteProduct();

        $optionShadeViolet = $this->createOptionTypeWithValue('SHADE', 'VIOLET');
        $optionFittingClassic = $this->createOptionTypeWithValue('FITTING', 'CLASSIC');

        $productOptionShade = $this->createProductOptionTypeUsage($product, $optionShadeViolet);
        $productOptionFitting = $this->createProductOptionTypeUsage($product, $optionFittingClassic);

        $idProductOptionValueUsageSmall = $this->facade->importProductOptionValueUsage($productOptionFitting->getIdProductOptionTypeUsage(),  'CLASSIC');
        $idProductOptionValueUsageViolet = $this->facade->importProductOptionValueUsage($productOptionShade->getIdProductOptionTypeUsage(),  'VIOLET');

        $this->facade->importProductOptionValueUsageConstraint($product->getSku(), $idProductOptionValueUsageSmall, 'VIOLET', 'NOT');
        $this->facade->importProductOptionValueUsageConstraint($product->getSku(), $idProductOptionValueUsageViolet, 'CLASSIC', 'NOT');
        $this->facade->flushBuffer();

        $result = SpyProductOptionValueUsageConstraintQuery::create()
            ->filterByFkProductOptionValueUsageA([$idProductOptionValueUsageSmall, $idProductOptionValueUsageViolet])
            ->filterByFkProductOptionValueUsageB([$idProductOptionValueUsageSmall, $idProductOptionValueUsageViolet])
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed asserting that method is idempotent');

        $this->performAssertionOnTouchTable($product->getFkAbstractProduct());
    }

    public function testImportProductOptionPresetConfiguration()
    {
        $product = $this->createConcreteProduct();
        $optionShade = $this->createOptionTypeWithValue('SHADE', 'VIOLET');
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
        $this->facade->flushBuffer();

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

    /**
     * @param string $typeKey
     * @param string $valueKey
     *
     * @throws PropelException
     *
     * @return SpyProductOptionType
     */
    private function createOptionTypeWithValue($typeKey, $valueKey)
    {
        $optionValue = (new SpyProductOptionValue())->setImportKey($valueKey);
        $optionType = (new SpyProductOptionType())->setImportKey($typeKey)->addSpyProductOptionValue($optionValue);
        $optionType->save();

        return $optionType;
    }

    /**
     * @param SpyProduct $product
     * @param SpyProductOptionType $optionType
     *
     * @throws PropelException
     *
     * @return SpyProductOptionTypeUsage
     */
    private function createProductOptionTypeUsage($product, $optionType)
    {
        $productOptionTypeUsage = (new SpyProductOptionTypeUsage())
            ->setSpyProduct($product)
            ->setSpyProductOptionType($optionType)
            ->setIsOptional(false);

        $productOptionTypeUsage->save();

        return $productOptionTypeUsage;
    }

    /**
     * @param int $idAbstractProduct
     */
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
