<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface getEntityManager()
 */
class CompanyUnitAddressFacade extends AbstractFacade implements CompanyUnitAddressFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function create(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->getFactory()->createCompanyUnitAddressWriter()->create($companyUnitAddressTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function update(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->getFactory()->createCompanyUnitAddressWriter()->update($companyUnitAddressTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function delete(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $this->getFactory()->createCompanyUnitAddressWriter()->delete($companyUnitAddressTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function getCompanyUnitAddressById(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->getRepository()->getCompanyUnitAddressById($companyUnitAddressTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): CompanyUnitAddressCollectionTransfer {

        return $this->getFactory()->createCompanyUnitAddressReader()->getCompanyUnitAddressCollection($companyUnitAddressCollectionTransfer);
    }
}
