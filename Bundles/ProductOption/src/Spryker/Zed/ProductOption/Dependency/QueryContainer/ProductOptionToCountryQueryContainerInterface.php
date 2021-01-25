<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Dependency\QueryContainer;

interface ProductOptionToCountryQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountries();
}
