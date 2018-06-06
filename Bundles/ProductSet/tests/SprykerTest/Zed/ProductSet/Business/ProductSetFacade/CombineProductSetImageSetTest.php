<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSet\Business\ProductSetFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageSetBuilder;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductSet
 * @group Business
 * @group ProductSetFacade
 * @group CombineProductSetImageSetTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductSet\ProductSetBusinessTester $tester
 */
class CombineProductSetImageSetTest extends Unit
{
    /**
     * @return void
     */
    public function testGetCombinedAbstractImageSets()
    {
        $localeTransfer = $this->tester->haveLocale();

        $defaultProductImageSetTransfer1 = (new ProductImageSetBuilder())
            ->seed([
                ProductImageSetTransfer::NAME => 'image-set-name',
            ])
            ->withAnotherProductImage()
            ->build();

        $defaultProductImageSetTransfer2 = (new ProductImageSetBuilder())
            ->seed([
                ProductImageSetTransfer::NAME => 'image-set-name-to-merge',
            ])
            ->withAnotherProductImage()
            ->build();

        $localizedProductImageSetTransfer1 = (new ProductImageSetBuilder())
            ->seed([
                ProductImageSetTransfer::NAME => 'image-set-name-to-merge',
                ProductImageSetTransfer::LOCALE => $localeTransfer,
            ])
            ->withAnotherProductImage([
                ProductImageTransfer::SORT_ORDER => 1,
            ])
            ->withAnotherProductImage([
                ProductImageTransfer::SORT_ORDER => 2,
            ])
            ->build();

        $productSetTransfer = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::IMAGE_SETS => [
                $defaultProductImageSetTransfer1->modifiedToArray(),
                $defaultProductImageSetTransfer2->modifiedToArray(),
                $localizedProductImageSetTransfer1->modifiedToArray(),
            ],
        ], $localeTransfer);

        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        $imageSetTransfers = $this->tester->getFacade()->getCombinedProductSetImageSets(
            $productSetTransfer->getIdProductSet(),
            $localeTransfer->getIdLocale()
        );

        $this->assertCount(2, $imageSetTransfers, 'Expected number of images sets should have been returned.');
        $this->assertCount(1, $imageSetTransfers[0]->getProductImages(), 'Product image set 1/2 should have expected number of images.');
        $this->assertCount(2, $imageSetTransfers[1]->getProductImages(), 'Product image set 2/2 should have expected number of images.');

        $this->assertSame(
            $localizedProductImageSetTransfer1->getProductImages()[0]->getExternalUrlSmall(),
            $imageSetTransfers[1]->getProductImages()[0]->getExternalUrlSmall(),
            'Product image 1/2 should have expected external small URL. ' . $imageSetTransfers[1]->getProductImages()[1]->getExternalUrlSmall()
        );

        $this->assertSame(
            $localizedProductImageSetTransfer1->getProductImages()[1]->getExternalUrlSmall(),
            $imageSetTransfers[1]->getProductImages()[1]->getExternalUrlSmall(),
            'Product image 2/2 should have expected external small URL.'
        );
    }
}
