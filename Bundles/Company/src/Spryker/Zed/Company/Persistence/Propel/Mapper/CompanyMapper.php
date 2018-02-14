<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\SpyCompany;

class CompanyMapper implements CompanyMapperInterface
{
    /**
     * @param \Orm\Zed\Company\Persistence\SpyCompany $companyEntity
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function mapCompanyEntityToTransfer(SpyCompany $companyEntity): CompanyTransfer
    {
        $companyTransfer = new CompanyTransfer();
        $companyTransfer->fromArray($companyEntity->toArray(), true);

        return $companyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompany
     */
    public function mapCompanyTransferToEntity(CompanyTransfer $companyTransfer): SpyCompany
    {
        $companyEntity = new SpyCompany();
        $companyEntity->fromArray($companyTransfer->modifiedToArray());
        $companyEntity->setNew($companyTransfer->getIdCompany() === null);

        if ($companyTransfer->getIsActive() !== null) {
            $companyEntity->setIsActive(!$companyTransfer->getIsActive())
                ->setIsActive($companyTransfer->getIsActive());
        }

        return $companyEntity;
    }
}
