<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductLabelStorage\ProductLabelStorageFactory getFactory()
 */
class ProductLabelStorageClient extends AbstractClient implements ProductLabelStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName, ?string $storeName = null)
    {
        if (!$storeName) {
            trigger_error(
                'Pass the $storeName parameter to be forward compatible with the next major version.',
                E_USER_DEPRECATED
            );
        }

        return $this
            ->getFactory()
            ->createProductAbstractLabelStorageReader()
            ->findLabelsByIdProductAbstract($idProductAbstract, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    public function getProductLabelsByProductAbstractIds(
        array $productAbstractIds,
        string $localeName,
        ?string $storeName = null
    ): array {
        if (!$storeName) {
            trigger_error(
                'Pass the $storeName parameter to be forward compatible with the next major version.',
                E_USER_DEPRECATED
            );
        }

        return $this->getFactory()
            ->createProductAbstractLabelStorageReader()
            ->getProductLabelsByProductAbstractIds($productAbstractIds, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idProductLabels
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName, ?string $storeName = null)
    {
        if (!$storeName) {
            trigger_error(
                'Pass the $storeName parameter to be forward compatible with the next major version.',
                E_USER_DEPRECATED
            );
        }

        return $this
            ->getFactory()
            ->createLabelDictionaryReader()
            ->findSortedLabelsByIdsProductLabel($idProductLabels, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $labelName
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName, ?string $storeName = null)
    {
        if (!$storeName) {
            trigger_error(
                'Pass the $storeName parameter to be forward compatible with the next major version.',
                E_USER_DEPRECATED
            );
        }

        return $this
            ->getFactory()
            ->createLabelDictionaryReader()
            ->findLabelByName($labelName, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductView(ProductViewTransfer $productViewTransfer, string $locale): ProductViewTransfer
    {
        return $this->getFactory()
            ->createProductViewExpander()
            ->expand($productViewTransfer, $locale);
    }
}
