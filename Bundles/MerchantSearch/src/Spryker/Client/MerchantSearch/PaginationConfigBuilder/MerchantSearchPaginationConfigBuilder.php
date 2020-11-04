<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\PaginationConfigBuilder;

use Spryker\Client\MerchantSearch\MerchantSearchConfig;

class MerchantSearchPaginationConfigBuilder implements PaginationConfigBuilderInterface
{
    /**
     * @var \Spryker\Client\MerchantSearch\MerchantSearchConfig
     */
    protected $merchantSearchConfig;

    /**
     * @param \Spryker\Client\MerchantSearch\MerchantSearchConfig $merchantSearchConfig
     */
    public function __construct(MerchantSearchConfig $merchantSearchConfig)
    {
        $this->merchantSearchConfig = $merchantSearchConfig;
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     */
    public function getCurrentPage(array $requestParameters): int
    {
        $paramName = $this->merchantSearchConfig->getPageParameterName();

        return isset($requestParameters[$paramName]) ? max((int)$requestParameters[$paramName], 1) : 1;
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     */
    public function getCurrentItemsPerPage(array $requestParameters): int
    {
        $paramName = $this->merchantSearchConfig->getItemsPerPageParameterName();

        if ($this->isValidItemsPerPage($requestParameters)) {
            return (int)$requestParameters[$paramName];
        }

        return $this->merchantSearchConfig->getDefaultItemsPerPage();
    }

    /**
     * @param array $requestParameters
     *
     * @return bool
     */
    protected function isValidItemsPerPage(array $requestParameters): bool
    {
        $perPage = $requestParameters[$this->merchantSearchConfig->getItemsPerPageParameterName()] ?? null;

        return $perPage > 0 && $perPage <= $this->merchantSearchConfig->getMaxItemsPerPage();
    }
}
