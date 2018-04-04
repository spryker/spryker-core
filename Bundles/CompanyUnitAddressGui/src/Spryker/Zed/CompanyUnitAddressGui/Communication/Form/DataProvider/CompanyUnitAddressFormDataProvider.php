<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface;

class CompanyUnitAddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     */
    public function __construct(
        CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
    ) {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CompanyUnitAddressTransfer::class,
        ];
    }

    /**
     * @param int|null $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getData($idCompanyUnitAddress = null)
    {
        if (!$idCompanyUnitAddress) {
            $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();

            return $companyUnitAddressTransfer;
        }

        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $companyUnitAddressTransfer = $this->companyUnitAddressFacade
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        return $companyUnitAddressTransfer;
    }
}
