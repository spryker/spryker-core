<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Dependency\Client;

class SearchRestApiToCatalogClientBridge implements SearchRestApiToCatalogClientInterface
{
    /**
     * @var \Spryker\Client\Catalog\CatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @param \Spryker\Client\Catalog\CatalogClientInterface $catalogClient
     */
    public function __construct($catalogClient)
    {
        $this->catalogClient = $catalogClient;
    }

    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSearch(string $searchString, array $requestParameters = []): array
    {
        return $this->catalogClient->catalogSearch($searchString, $requestParameters);
    }

    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSuggestSearch(string $searchString, array $requestParameters = []): array
    {
        return $this->catalogClient->catalogSuggestSearch($searchString, $requestParameters);
    }
}
