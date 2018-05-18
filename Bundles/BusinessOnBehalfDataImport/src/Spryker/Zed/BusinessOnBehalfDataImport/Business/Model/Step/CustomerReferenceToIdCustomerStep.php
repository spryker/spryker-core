<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CustomerReferenceToIdCustomerStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCustomerCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $customerReference = $dataSet[BusinessOnBehalfDataSet::CUSTOMER_REFERENCE];
        if (!isset($this->idCustomerCache[$customerReference])) {
            $customerQuery = SpyCustomerQuery::create();
            $idCustomer = $customerQuery
                ->select(SpyCustomerTableMap::COL_ID_CUSTOMER)
                ->findOneByCustomerReference($customerReference);

            if (!$idCustomer) {
                throw new EntityNotFoundException(sprintf('Could not find customer by reference "%s"', $customerReference));
            }

            $this->idCustomerCache[$customerReference] = $idCustomer;
        }

        $dataSet[BusinessOnBehalfDataSet::ID_CUSTOMER] = $this->idCustomerCache[$customerReference];
    }
}
