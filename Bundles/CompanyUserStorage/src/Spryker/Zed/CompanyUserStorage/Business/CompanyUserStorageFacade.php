<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyUserStorage\Business\CompanyUserStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface getRepository()
 */
class CompanyUserStorageFacade extends AbstractFacade implements CompanyUserStorageFacadeInterface
{
    /**
     * @api
     *
     * @param int[] $companyUserIds
     *
     * @return void
     */
    public function publish(array $companyUserIds): void
    {
        $this->getFactory()
            ->createCompanyUserStorageWriter()
            ->publish($companyUserIds);
    }

    /**
     * @api
     *
     * @param int[] $companyUserIds
     *
     * @return void
     */
    public function unpublish(array $companyUserIds): void
    {
        $this->getFactory()
            ->createCompanyUserStorageWriter()
            ->unpublish($companyUserIds);
    }
}
