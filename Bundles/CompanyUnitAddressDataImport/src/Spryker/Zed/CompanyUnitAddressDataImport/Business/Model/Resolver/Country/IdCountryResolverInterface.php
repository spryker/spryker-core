<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Country;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

interface IdCountryResolverInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    public function getIdCountry(DataSetInterface $dataSet): int;
}
