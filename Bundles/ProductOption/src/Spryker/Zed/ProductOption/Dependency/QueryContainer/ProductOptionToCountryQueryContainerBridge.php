<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Dependency\QueryContainer;

use Orm\Zed\Country\Persistence\SpyCountryQuery;

class ProductOptionToCountryQueryContainerBridge implements ProductOptionToCountryQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface
     */
    protected $countryQueryContainer;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface $countryQueryContainer
     */
    public function __construct($countryQueryContainer)
    {
        $this->countryQueryContainer = $countryQueryContainer;
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountries(): SpyCountryQuery
    {
        return $this->countryQueryContainer->queryCountries();
    }
}
