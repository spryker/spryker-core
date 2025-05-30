<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSet;

use Codeception\Actor;
use Generated\Shared\DataBuilder\LocalizedProductSetBuilder;
use Generated\Shared\DataBuilder\ProductImageSetBuilder;
use Generated\Shared\DataBuilder\ProductSetBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;

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
 * @method \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductSetBusinessTester extends Actor
{
    use _generated\ProductSetBusinessTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_IMAGE_SET_NAME = 'image-set-name';

    /**
     * @param array $productSetSeed
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function generateProductSetTransfer(array $productSetSeed = [], ?LocaleTransfer $localeTransfer = null)
    {
        if (!$localeTransfer) {
            $localeTransfer = $this->haveLocale();
        }

        $productSetTransfer = (new ProductSetBuilder())
            ->seed($productSetSeed)
            ->withLocalizedData(
                (new LocalizedProductSetBuilder())
                    ->seed([
                        LocalizedProductSetTransfer::LOCALE => $localeTransfer,
                    ])
                    ->withProductSetData(),
            )
            ->withImageSet(
                (new ProductImageSetBuilder([
                    ProductImageSetTransfer::LOCALE => null,
                    ProductImageSetTransfer::NAME => static::DEFAULT_IMAGE_SET_NAME,
                ]))
                    ->withProductImage(),
            )
            ->build();

        return $productSetTransfer;
    }
}
