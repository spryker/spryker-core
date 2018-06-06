<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfCompanyUserDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CustomerReferenceToIdCustomerStep implements DataImportStepInterface
{
    /**
     * @var int[] Keys are customer references
     */
    protected $idCustomerBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $customerReference = $dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_CUSTOMER_REFERENCE];

        $dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_CUSTOMER] = $this->getIdCustomer($customerReference);
    }

    /**
     * @uses SpyCustomerQuery
     *
     * @param string $customerReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCustomer(string $customerReference): int
    {
        if (isset($this->idCustomerBuffer[$customerReference])) {
            return $this->idCustomerBuffer[$customerReference];
        }

        $idCustomer = SpyCustomerQuery::create()
            ->select(SpyCustomerTableMap::COL_ID_CUSTOMER)
            ->findOneByCustomerReference($customerReference);

        if (!$idCustomer) {
            throw new EntityNotFoundException(sprintf('Could not find customer by reference "%s"', $customerReference));
        }

        $this->idCustomerBuffer[$customerReference] = $idCustomer;

        return $this->idCustomerBuffer[$customerReference];
    }
}
