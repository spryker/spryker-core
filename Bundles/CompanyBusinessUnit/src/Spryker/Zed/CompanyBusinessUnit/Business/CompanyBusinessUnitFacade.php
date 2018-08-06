<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface getEntityManager()
 */
class CompanyBusinessUnitFacade extends AbstractFacade implements CompanyBusinessUnitFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        return $this->getRepository()
            ->getCompanyBusinessUnitById(
                $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function create(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitCreator()
            ->create($companyBusinessUnitTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function createByCompany(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitCreator()
            ->createByCompany($companyResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function update(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->getFactory()
            ->createCompanyBusinessUnitWriter()
            ->update($companyBusinessUnitTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function delete(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->getFactory()
            ->createCompanyBusinessUnitWriter()
            ->delete($companyBusinessUnitTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        return $this->getRepository()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    public function findDefaultBusinessUnitByCompanyId(int $idCompany): ?CompanyBusinessUnitTransfer
    {
        return $this->getRepository()
            ->findDefaultBusinessUnitByCompanyId($idCompany);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function assignDefaultBusinessUnitToCompanyUser(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer {
        return $this->getFactory()
            ->createCompanyBusinessUnitAssigner()
            ->assignDefaultBusinessUnitToCompanyUser($companyUserResponseTransfer);
    }
}
