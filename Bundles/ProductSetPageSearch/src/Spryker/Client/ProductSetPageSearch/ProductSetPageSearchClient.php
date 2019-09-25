<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetPageSearch;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductSetPageSearch\ProductSetPageSearchFactory getFactory()
 */
class ProductSetPageSearchClient extends AbstractClient implements ProductSetPageSearchClientInterface
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
}
