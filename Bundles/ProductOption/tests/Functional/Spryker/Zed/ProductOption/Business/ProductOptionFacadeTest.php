<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductOption\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;

class ProductOptionFacadeTest extends Test
{
    const DEFAULT_LOCALE_ISO_CODE = 'en_US';

    /**
     * @return void
     */
    public function testSaveProductOptionGroupShouldPersistProvidedOption()
    {
        $productOptionFacade = $this->createProductFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertNotEmpty($idOfProductOptionGroup);
        $this->assertEquals($productOptionGroupTransfer->getName(), $productOptionGroupEntity->getName());
        $this->assertSame($productOptionGroupTransfer->getActive(), $productOptionGroupEntity->getActive());

        $productOptionValues = $productOptionGroupEntity->getSpyProductOptionValues();
        $productOptionValueEntity = $productOptionValues[0];

        $this->assertSame($productOptionValueEntity->getPrice(), $producOptionValueTransfer->getPrice());
        $this->assertEquals($producOptionValueTransfer->getValue(), $productOptionValueEntity->getValue());
        $this->assertEquals($producOptionValueTransfer->getSku(), $productOptionValueEntity->getSku());

        $glossaryFacade = $this->createGlossaryFacade();
        $localeTransfer = $this->createLocaleTransfer();

        $this->assertTrue($glossaryFacade->hasTranslation($producOptionValueTransfer->getValue(), $localeTransfer));
        $this->assertTrue($glossaryFacade->hasTranslation($productOptionGroupTransfer->getName(), $localeTransfer));
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndAssignProductAbstract()
    {
        $productOptionFacade = $this->createProductFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $productAbstractEntity = $this->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $assignedProductAbstractEntity = $productOptionGroupEntity->getSpyProductAbstracts()[0];

        $this->assertEquals($assignedProductAbstractEntity->getSku(), $productAbstractEntity->getSku());
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndDeAssignProductAbstract()
    {
        $productOptionFacade = $this->createProductFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $productAbstractEntity = $this->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setProductsToBeAssigned([]);
        $productOptionGroupTransfer->setProductsToBeDeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertEmpty($productOptionGroupEntity->getSpyProductAbstracts());
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndRemoveProductOptionValues()
    {
        $productOptionFacade = $this->createProductFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $productAbstractEntity = $this->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setProductsToBeAssigned([]);
        $productOptionGroupTransfer->setProductOptionValuesToBeRemoved([$producOptionValueTransfer->getIdProductOptionValue()]);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertEmpty($productOptionGroupEntity->getSpyProductOptionValues());
    }


    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createProductAbstract($sku)
    {
        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity->setSku($sku);
        $productAbstractEntity->setAttributes('');
        $productAbstractEntity->save();

        return $productAbstractEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected function createProductOptionGroupTransfer(ProductOptionValueTransfer $productOptionValueTransfer)
    {
        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->setName('translation.key');
        $productOptionGroupTransfer->setActive(true);

        $groupNameTranslationTransfer = new ProductOptionTranslationTransfer();
        $groupNameTranslationTransfer->setKey($productOptionGroupTransfer->getName());
        $groupNameTranslationTransfer->setLocaleCode(self::DEFAULT_LOCALE_ISO_CODE);
        $groupNameTranslationTransfer->setName('Translation1');
        $productOptionGroupTransfer->addGroupNameTranslation($groupNameTranslationTransfer);

        $productOptionTranslationTransfer = clone $groupNameTranslationTransfer;
        $productOptionTranslationTransfer->setKey($productOptionValueTransfer->getValue());
        $productOptionTranslationTransfer->setName('value translation');
        $productOptionTranslationTransfer->setLocaleCode(self::DEFAULT_LOCALE_ISO_CODE);
        $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionTranslationTransfer);

        return $productOptionGroupTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected function createProductOptionValueTransfer()
    {
        $producOptionValueTransfer = new ProductOptionValueTransfer();
        $producOptionValueTransfer->setValue('value.translation.key');
        $producOptionValueTransfer->setPrice(1000);
        $producOptionValueTransfer->setSku('sku_for_testing');

        return $producOptionValueTransfer;
    }

    /**
     * @return \Spryker\Zed\Glossary\Business\GlossaryFacade
     */
    protected function createGlossaryFacade()
    {
        $glossaryFacade = new GlossaryFacade();

        return $glossaryFacade;
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\ProductOptionFacade
     */
    protected function createProductFacade()
    {
        $productOptionFacade = new ProductOptionFacade();

        return $productOptionFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransfer()
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName(self::DEFAULT_LOCALE_ISO_CODE);

        return $localeTransfer;
    }

}
