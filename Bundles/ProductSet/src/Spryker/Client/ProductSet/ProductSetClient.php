<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSet;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductSet\ProductSetFactory getFactory()
 */
class ProductSetClient extends AbstractClient implements ProductSetClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getProductSetList($limit = null, $offset = null)
    {
        $searchQuery = $this->getFactory()->createProductSetListQuery($limit, $offset);
        $resultFormatters = $this->getFactory()->createProductSetListResultFormatters();

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    public function mapProductSetStorageDataToTransfer(array $productSetStorageData)
    {
        return $this
            ->getFactory()
            ->createProductSetStorageMapper()
            ->mapDataToTransfer($productSetStorageData);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer|null
     */
    public function findProductSetByIdProductSet($idProductSet)
    {
        return $this->getFactory()
            ->createProductSetStorage()
            ->findProductSetByIdProductSet($idProductSet);
    }
}
