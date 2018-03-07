<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business\Model;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface;

class CompanyUnitAddressHydrator implements CompanyUnitAddressHydratorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface
     */
    protected $companyUnitAddressRepository;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface $companyUnitAddressRepository
     */
    public function __construct(CompanyUnitAddressLabelRepositoryInterface $companyUnitAddressRepository)
    {
        $this->companyUnitAddressRepository = $companyUnitAddressRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function hydrate(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        $labelCollection = $this->companyUnitAddressRepository
            ->findCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());
        $companyUnitAddressTransfer->setLabelCollection($labelCollection);

        return $companyUnitAddressTransfer;
    }
}
