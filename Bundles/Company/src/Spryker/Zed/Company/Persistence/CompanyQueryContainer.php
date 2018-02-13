<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Company\Persistence\CompanyPersistenceFactory getFactory()
 */
class CompanyQueryContainer extends AbstractQueryContainer implements CompanyQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    public function queryCompany(): SpyCompanyQuery
    {
        return $this->getFactory()->createCompanyQuery();
    }
}
