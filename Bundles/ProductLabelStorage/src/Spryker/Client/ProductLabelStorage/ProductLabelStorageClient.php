<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductLabelStorage\ProductLabelStorageFactory getFactory()
 */
class ProductLabelStorageClient extends AbstractClient implements ProductLabelStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName)
    {
        return $this
            ->getFactory()
            ->createProductAbstractLabelStorageReader()
            ->findLabelsByIdProductAbstract($idProductAbstract, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    public function getLabelsByProductAbstractIds(array $productAbstractIds, string $localeName): array
    {
        return $this
            ->getFactory()
            ->createProductAbstractLabelStorageReader()
            ->getLabelsByProductAbstractIds($productAbstractIds, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $idProductLabels
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName)
    {
        return $this
            ->getFactory()
            ->createLabelDictionaryReader()
            ->findSortedLabelsByIdsProductLabel($idProductLabels, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName)
    {
        return $this
            ->getFactory()
            ->createLabelDictionaryReader()
            ->findLabelByName($labelName, $localeName);
    }
}
