<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage;

use Codeception\Actor;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;

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
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductImage\PHPMD)
 */
class ProductImageBusinessTester extends Actor
{
    use _generated\ProductImageBusinessTesterActions;

    /**
     * @param string $name
     * @param int|null $fkProductAbstract
     * @param int|null $fkProduct
     * @param int|null $fkLocale
     *
     * @return void
     */
    public function createProductImageSet(string $name, ?int $fkProductAbstract, ?int $fkProduct, ?int $fkLocale): void
    {
        $imageSetConcrete = new SpyProductImageSet();
        $imageSetConcrete
            ->setName($name)
            ->setFkProductAbstract($fkProductAbstract)
            ->setFkProduct($fkProduct)
            ->setFkLocale($fkLocale)
            ->save();
    }

    /**
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool
    {
        return (bool)getenv('SPRYKER_DYNAMIC_STORE_MODE');
    }

    /**
     * @param array<string, array<string, string>> $translationsData
     *
     * @return void
     */
    public function haveTranslations(array $translationsData): void
    {
        foreach ($translationsData as $glossaryKey => $translationsIndexedByLocaleName) {
            $this->haveTranslation([
                KeyTranslationTransfer::GLOSSARY_KEY => $glossaryKey,
                KeyTranslationTransfer::LOCALES => $translationsIndexedByLocaleName,
            ]);
        }
    }
}
