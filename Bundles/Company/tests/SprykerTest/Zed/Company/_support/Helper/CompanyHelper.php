<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Company\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    public function findCompanyById(int $idCompany): ?CompanyTransfer
    {
        $entity = SpyCompanyQuery::create()
            ->filterByIdCompany($idCompany)
            ->findOne();

        if ($entity !== null) {
            return (new CompanyTransfer())->fromArray($entity->toArray(), true);
        }

        return null;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveCompany(array $seedData = []): CompanyTransfer
    {
        $companyTransfer = (new CompanyBuilder($seedData))->build();
        $companyTransfer->setIdCompany(null);

        $companyTransfer = $this->getLocator()->company()->facade()->create($companyTransfer)->getCompanyTransfer();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyTransfer) {
            $this->getCompanyFacade()->delete($companyTransfer);
        });

        return $companyTransfer;
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface
     */
    protected function getCompanyFacade(): CompanyFacadeInterface
    {
        return $this->getLocator()->company()->facade();
    }
}
