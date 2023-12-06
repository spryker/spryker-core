<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductSearchAttributeBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductSearch\PHPMD)
 */
class ProductSearchBusinessTester extends Actor
{
    use _generated\ProductSearchBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductAttributeKeyTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->createProductAttributeKeyQuery());
    }

    /**
     * @param array $productAttributeKeySeedData
     * @param array $productSearchAttributeSeedData
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function haveProductSearchAttribute(
        array $productAttributeKeySeedData = [],
        array $productSearchAttributeSeedData = []
    ): ProductSearchAttributeTransfer {
        $productAttributeKeyEntity = $this->haveProductAttributeKeyEntity($productAttributeKeySeedData);

        $productSearchAttributeTransfer = (new ProductSearchAttributeBuilder($productSearchAttributeSeedData))->build();

        $productSearchAttributeEntity = (new SpyProductSearchAttribute())
            ->fromArray($productSearchAttributeTransfer->toArray())
            ->setFkProductAttributeKey($productAttributeKeyEntity->getIdProductAttributeKey());

        $productSearchAttributeEntity->save();

        return (new ProductSearchAttributeTransfer())->fromArray($productSearchAttributeEntity->toArray(), true);
    }

    /**
     * @param string $glossaryKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $translation
     *
     * @return void
     */
    public function addProductSearchKeyTranslation(
        string $glossaryKey,
        LocaleTransfer $localeTransfer,
        string $translation
    ): void {
        if (!$this->getLocator()->glossary()->facade()->hasKey($glossaryKey)) {
            $this->getLocator()->glossary()->facade()->createKey($glossaryKey);
        }
        $this->getLocator()->glossary()->facade()->createTranslation(
            $glossaryKey,
            $localeTransfer,
            $translation,
        );
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function createProductAttributeKeyQuery(): SpyProductAttributeKeyQuery
    {
        return SpyProductAttributeKeyQuery::create();
    }
}
