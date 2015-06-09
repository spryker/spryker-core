<?php

namespace Functional\SprykerFeature\Zed\ProductOption\Business\Model;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;
use Generated\Zed\Ide\AutoCompletion;

use SprykerFeature\Zed\ProductOption\Persistence\Propel\Base\SpyConfigurationPresetQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyConfigurationPresetValueQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyOptionTypeQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeExclusionQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeQuery;

use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyOptionType;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyOptionValue;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionType;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValue;

use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueConstraintQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueQuery;

/**
 * @group Business
 * @group Zed
 * @group ProdutOptions
 * @group KeyBasedWriterTest
 */
class DataImportWriterTest extends Test
{

    /**
     * @var ProductOptionFacade
     */
    private $facade;

    /**
     * @var AutoCompletion $locator
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->facade = new ProductOptionFacade(new Factory('ProductOption'), $this->locator);
    }

    public function testImportOptionType()
    {
        $this->facade->importOptionType('SHADE', ['en_GB' => 'Shade']);
        $this->facade->importOptionType('SHADE', ['en_GB' => 'Shade']);

        $result = SpyOptionTypeQuery::create()->findByImportKey('SHADE');

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');
        $this->assertEquals('SHADE', $result[0]->getImportKey());
        $this->assertEquals('Shade', $result[0]->getSpyOptionTypeTranslations()[0]->getName());
    }

    public function testImportOptionValue()
    {
        $optionType = (new SpyOptionType)->setImportKey('SHADE');
        $optionType->save();

        $this->facade->importOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '2.99');
        $this->facade->importOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '2.99');

        $result = SpyOptionTypeQuery::create()
            ->findByImportKey('SHADE');

        $this->assertEquals('SHADE', $result[0]->getImportKey());
        $this->assertEquals(1, $result->count());

        $optionValues = $result[0]->getSpyOptionValues();
        $this->assertEquals(1, $optionValues->count(), 'Failed assetting that method is idempotent');

        $this->assertEquals('VIOLET', $optionValues[0]->getImportKey());
        $this->assertEquals(299, $optionValues[0]->getSpyOptionValuePrice()->getPrice());
        $this->assertEquals('Violet', $optionValues[0]->getSpyOptionValueTranslations()[0]->getName());
    }

    public function testImportProductOptionType()
    {
        $product = $this->createConcreteProduct();

        $optionType = (new SpyOptionType)->setImportKey('SHADE');
        $optionType->save();

        $this->facade->importProductOptionType('ABC123', 'SHADE');
        $this->facade->importProductOptionType('ABC123', 'SHADE');

        $result = SpyProductOptionTypeQuery::create()
            ->filterByFkOptionType($optionType->getIdOptionType())
            ->filterByFkProduct($product->getIdProduct())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');
    }

    public function testImportProductOptionValue()
    {
        $product = $this->createConcreteProduct();
        $optionType = $this->createOptionTypeWithValue();
        $productOptionType = $this->createProductOptionType($product, $optionType);

        $this->facade->importProductOptionValue($productOptionType->getIdProductOptionType(),  'VIOLET');
        $this->facade->importProductOptionValue($productOptionType->getIdProductOptionType(),  'VIOLET');

        $result = SpyProductOptionValueQuery::create()
            ->filterByFkProductOptionType($productOptionType->getIdProductOptionType())
            ->filterByFkOptionValue($optionType->getSpyOptionValues()[0]->getIdOptionValue())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');
    }

    public function testImportProductOptionTypeExclusion()
    {
        $product = $this->createConcreteProduct();
        $optionShadeViolet = $this->createOptionTypeWithValue();
        $optionFittingClassic = $this->createOptionTypeWithValue('FITTING', 'CLASSIC');

        $productOptionFitting = $this->createProductOptionType($product, $optionFittingClassic);
        $productOptionShade = $this->createProductOptionType($product, $optionShadeViolet);

        $this->facade->importProductOptionTypeExclusion($product->getSku(), 'SHADE', 'FITTING');
        $this->facade->importProductOptionTypeExclusion($product->getSku(), 'SHADE', 'FITTING');

        $result = SpyProductOptionTypeExclusionQuery::create()
            ->filterByFkProductOptionTypeA($productOptionShade->getIdProductOptionType())
            ->filterByFkProductOptionTypeB($productOptionFitting->getIdProductOptionType())
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');
    }

    public function testImportProductOptionValueConstraint()
    {
        $product = $this->createConcreteProduct();

        $optionShadeViolet = $this->createOptionTypeWithValue();
        $optionFittingClassic = $this->createOptionTypeWithValue('FITTING', 'CLASSIC');

        $productOptionFitting = $this->createProductOptionType($product, $optionFittingClassic);
        $productOptionShade = $this->createProductOptionType($product, $optionShadeViolet);

        $idProductOptionValueSmall = $this->facade->importProductOptionValue($productOptionFitting->getIdProductOptionType(),  'CLASSIC');
        $idProductOptionValueViolet = $this->facade->importProductOptionValue($productOptionShade->getIdProductOptionType(),  'VIOLET');

        $this->facade->importProductOptionValueConstraint($product->getSku(), $idProductOptionValueSmall, 'VIOLET', 'NOT');
        $this->facade->importProductOptionValueConstraint($product->getSku(), $idProductOptionValueViolet, 'CLASSIC', 'NOT');

        $result = SpyProductOptionValueConstraintQuery::create()
            ->filterByFkProductOptionValueA([$idProductOptionValueSmall, $idProductOptionValueViolet])
            ->filterByFkProductOptionValueB([$idProductOptionValueSmall, $idProductOptionValueViolet])
            ->find();

        $this->assertEquals(1, $result->count(), 'Failed assetting that method is idempotent');
    }

    public function testImportPresetConfiguration()
    {
        $product = $this->createConcreteProduct();
        $optionShade = $this->createOptionTypeWithValue();
        $optionFitting = $this->createOptionTypeWithValue('FITTING', 'CLASSIC');

        $productOptionShade = $this->createProductOptionType($product, $optionShade);
        $productOptionValueViolet = (new SpyProductOptionValue)
            ->setSpyOptionValue($optionShade->getSpyOptionValues()[0]);
        $productOptionShade->addSpyProductOptionValue($productOptionValueViolet);
        $productOptionShade->save();

        $productOptionFitting = $this->createProductOptionType($product, $optionFitting);
        $productOptionValueSmall = (new SpyProductOptionValue)
            ->setSpyOptionValue($optionFitting->getSpyOptionValues()[0]);
        $productOptionFitting->addSpyProductOptionValue($productOptionValueSmall);
        $productOptionFitting->save();

        $this->facade->importPresetConfiguration($product->getSku(), ['VIOLET', 'CLASSIC']);

        $result = SpyConfigurationPresetQuery::create()->findByFkProduct($product->getIdProduct());
        $this->assertEquals(1, $result->count());
        $values = $result[0]->getSpyConfigurationPresetValues();
        foreach($values as $value) {
            $this->assertContains($value->getFkProductOptionValue(), [$productOptionValueSmall->getIdProductOptionValue(), $productOptionValueViolet->getIdProductOptionValue()]);
        }
    }

    private function createConcreteProduct()
    {
        $abstractProduct = (new SpyAbstractProduct())->setSku('ABC123');
        $abstractProduct->save();
        $product = (new SpyProduct)->setSku('ABC123')->setIsActive(true)->setSpyAbstractProduct($abstractProduct);

        $product->save();

        return $product;
    }

    private function createOptionTypeWithValue($typeKey = 'SHADE', $valueKey = 'VIOLET')
    {
        $optionValue = (new SpyOptionValue)->setImportKey($valueKey);
        $optionType = (new SpyOptionType)->setImportKey($typeKey)->addSpyOptionValue($optionValue);
        $optionType->save();

        return $optionType;
    }

    private function createProductOptionType($product, $optionType)
    {
        $productOptionType = (new SpyProductOptionType())
            ->setSpyProduct($product)
            ->setSpyOptionType($optionType)
            ->setIsOptional(false);

        $productOptionType->save();

        return $productOptionType;
    }
}
