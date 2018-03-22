<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Company;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

interface IdCompanyResolverInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    public function getIdCompany(DataSetInterface $dataSet): int;
}
