<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence;

interface CompanyQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idCompany
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    public function queryCompanyById($idCompany);
}
