<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer;

interface CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerInterface
{
    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddress();
}
