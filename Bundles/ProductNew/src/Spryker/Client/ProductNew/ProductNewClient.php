<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductNew\ProductNewFactory getFactory()
 */
class ProductNewClient extends AbstractClient implements ProductNewClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $requestParameters
     *
     * @return array
     */
    public function findNewProducts(array $requestParameters = [])
    {
        $searchQuery = $this->getFactory()->getNewProductsQueryPlugin($requestParameters);
        $resultFormatters = $this->getFactory()->getNewProductsSearchResultFormatterPlugins();

        return $this->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }
}
