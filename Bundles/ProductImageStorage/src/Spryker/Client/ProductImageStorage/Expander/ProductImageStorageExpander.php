<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToGlossaryStorageClientInterface;

class ProductImageStorageExpander implements ProductImageStorageExpanderInterface
{
    /**
     * @param \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(protected ProductImageStorageToGlossaryStorageClientInterface $glossaryStorageClient)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer $productAbstractImageStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer
     */
    public function expandProductAbstractImageStorageTransferWithProductImageAlternativeTexts(
        ProductAbstractImageStorageTransfer $productAbstractImageStorageTransfer,
        string $localeName
    ): ProductAbstractImageStorageTransfer {
        $this->expandProductImageSetStorageTransfersWithProductImageAlternativeTexts(
            $productAbstractImageStorageTransfer->getImageSets(),
            $localeName,
        );

        return $productAbstractImageStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer $productConcreteImageStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer
     */
    public function expandProductConcreteImageStorageTransferWithProductImageAlternativeTexts(
        ProductConcreteImageStorageTransfer $productConcreteImageStorageTransfer,
        string $localeName
    ): ProductConcreteImageStorageTransfer {
        $this->expandProductImageSetStorageTransfersWithProductImageAlternativeTexts(
            $productConcreteImageStorageTransfer->getImageSets(),
            $localeName,
        );

        return $productConcreteImageStorageTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetStorageTransfer> $productImageSetStorageTransfers
     * @param string $localeName
     *
     * @return void
     */
    protected function expandProductImageSetStorageTransfersWithProductImageAlternativeTexts(
        ArrayObject $productImageSetStorageTransfers,
        string $localeName
    ): void {
        $glossaryStorageKeys = $this->getGlossaryStorageKeys($productImageSetStorageTransfers);

        if (!$glossaryStorageKeys) {
            return;
        }

        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        foreach ($productImageSetStorageTransfers as $productImageSetStorageTransfer) {
            foreach ($productImageSetStorageTransfer->getImages() as $productImageStorageTransfer) {
                $this->setSmallImageAltText($productImageStorageTransfer, $translations);
                $this->setLargeImageAltText($productImageStorageTransfer, $translations);
            }
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetStorageTransfer> $productImageSetStorageTransfers
     *
     * @return list<string>
     */
    protected function getGlossaryStorageKeys(ArrayObject $productImageSetStorageTransfers): array
    {
        $glossaryStorageKeys = [];

        foreach ($productImageSetStorageTransfers as $productImageSetStorageTransfer) {
            foreach ($productImageSetStorageTransfer->getImages() as $productImageStorageTransfer) {
                $glossaryStorageKeys[] = $productImageStorageTransfer->getAltTextSmall();
                $glossaryStorageKeys[] = $productImageStorageTransfer->getAltTextLarge();
            }
        }

        return array_filter($glossaryStorageKeys);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageStorageTransfer $productImageStorageTransfer
     * @param list<string> $translations
     *
     * @return void
     */
    protected function setSmallImageAltText(
        ProductImageStorageTransfer $productImageStorageTransfer,
        array $translations
    ): void {
        if (
            !isset($translations[$productImageStorageTransfer->getAltTextSmall()])
            || $translations[$productImageStorageTransfer->getAltTextSmall()] === $productImageStorageTransfer->getAltTextSmall()
        ) {
            $productImageStorageTransfer->setAltTextSmall(null);

            return;
        }

        $productImageStorageTransfer->setAltTextSmall($translations[$productImageStorageTransfer->getAltTextSmall()]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageStorageTransfer $productImageStorageTransfer
     * @param list<string> $translations
     *
     * @return void
     */
    protected function setLargeImageAltText(
        ProductImageStorageTransfer $productImageStorageTransfer,
        array $translations
    ): void {
        if (
            !isset($translations[$productImageStorageTransfer->getAltTextLarge()])
            || $translations[$productImageStorageTransfer->getAltTextLarge()] === $productImageStorageTransfer->getAltTextLarge()
        ) {
            $productImageStorageTransfer->setAltTextLarge(null);

            return;
        }

        $productImageStorageTransfer->setAltTextLarge($translations[$productImageStorageTransfer->getAltTextLarge()]);
    }
}
