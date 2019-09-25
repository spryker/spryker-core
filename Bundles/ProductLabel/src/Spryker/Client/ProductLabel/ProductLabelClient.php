<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductLabel\ProductLabelFactory getFactory()
 */
class ProductLabelClient extends AbstractClient implements ProductLabelClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName)
    {
        return $this
            ->getFactory()
            ->createProductAbstractRelationReader()
            ->findLabelsByIdProductAbstract($idProductAbstract, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idProductLabels
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName)
    {
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
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findLabelByLocalizedName($labelName, $localeName)
    {
        return $this
            ->getFactory()
            ->createLabelDictionaryReader()
            ->findLabelByLocalizedName($labelName, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findLabelByName($labelName, $localeName)
    {
        return $this
            ->getFactory()
            ->createLabelDictionaryReader()
            ->findLabelByName($labelName, $localeName);
    }
}
