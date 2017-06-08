<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetCollector;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductSetCollector\ProductSetCollectorFactory getFactory()
 */
class ProductSetCollectorClient extends AbstractClient implements ProductSetCollectorClientInterface
{

    /**
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
