<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductOption\Business\Model;

use Codeception\TestCase\Test;
use Orm\Zed\ProductOption\Persistence\Base\SpyProductOptionConfigurationPresetQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionType;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsage;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageExclusionQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsage;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageConstraintQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;

/**
 * @group Business
 * @group Zed
 * @group ProductOption
 * @group DataImportWriterTest
 *
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacade getFacade()
 */
class DataImportWriterTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductOption\Business\ProductOptionFacade
     */
    private $facade;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->facade = new ProductOptionFacade();
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function testTouchUpdatedWhenUpdatingProductOptionType()
    {
        $product = $this->createProductConcrete();
        $optionType = $this->createOptionTypeWithValue('SHADE', 'VIOLET');
        $this->createProductOptionTypeUsage($product, $optionType);

        $this->facade->importProductOptionType('SHADE', ['en_GB' => 'Shade']);
        $this->facade->flushBuffer();

        $this->performAssertionOnTouchTable($product->getFkProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchUpdatedWhenUpdatingProductOptionValue()
    {
        $product = $this->createProductConcrete();
        $optionType = $this->createOptionTypeWithValue('SHADE', 'VIOLET');
        $optionTypeUsage = $this->createProductOptionTypeUsage($product, $optionType);
        $optionValueUsage = (new SpyProductOptionValueUsage())
            ->setFkProductOptionTypeUsage($optionTypeUsage->getIdProductOptionTypeUsage())
            ->setFkProductOptionValue($optionType->getSpyProductOptionValues()[0]->getIdProductOptionValue());

        $optionValueUsage->save();

        $this->facade->importProductOptionValue('VIOLET', 'SHADE', ['en_GB' => 'Violet'], '2.99');
        $this->facade->flushBuffer();

        $this->performAssertionOnTouchTable($product->getFkProductAbstract());
    }

    /**
     * @return void
     */
    public function testImportProductOptionTypeUsage()
    {
        $product = $this->createProductConcrete();

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

        $this->performAssertionOnTouchTable($product->getFkProductAbstract());
    }

    /**
     * @return void
     */
    public function testImportProductOptionValueUsage()
    {
        $product = $this->createProductConcrete();
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

        $this->performAssertionOnTouchTable($product->getFkProductAbstract());
    }

    /**
     * @return void
     */
    public function testImportProductOptionTypeUsageExclusion()
    {
        $product = $this->createProductConcrete();
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

        $this->performAssertionOnTouchTable($product->getFkProductAbstract());
    }

    /**
     * @return void
     */
    public function testImportProductOptionValueUsageConstraint()
    {
        $product = $this->createProductConcrete();

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

        $this->performAssertionOnTouchTable($product->getFkProductAbstract());
    }

    /**
     * @return void
     */
    public function testImportProductOptionPresetConfiguration()
    {
        $product = $this->createProductConcrete();
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

        $this->performAssertionOnTouchTable($product->getFkProductAbstract());
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    private function createProductConcrete()
    {
        $productAbstract = (new SpyProductAbstract())
            ->setSku('ABC123')
            ->setAttributes('{}');
        $productAbstract->save();

        $product = (new SpyProduct())
            ->setSku('ABC123')
            ->setAttributes('{}')
            ->setIsActive(true)
            ->setSpyProductAbstract($productAbstract);

        $product->save();

        return $product;
    }

    /**
     * @param string $typeKey
     * @param string $valueKey
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionType
     */
    private function createOptionTypeWithValue($typeKey, $valueKey)
    {
        $optionValue = (new SpyProductOptionValue())->setImportKey($valueKey);
        $optionType = (new SpyProductOptionType())->setImportKey($typeKey)->addSpyProductOptionValue($optionValue);
        $optionType->save();

        return $optionType;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $product
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionType $optionType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsage
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
     * @param int $idProductAbstract
     *
     * @return void
     */
    private function performAssertionOnTouchTable($idProductAbstract)
    {
        $query = SpyTouchQuery::create()
            ->filterByItemType('product_abstract')
            ->limit(1)
            ->orderByIdTouch('desc')
            ->find();

        $this->assertEquals(1, $query->count());
        foreach ($query as $touchEntity) {
            $this->assertEquals($touchEntity->getItemId(), $idProductAbstract);
        }
    }

}
