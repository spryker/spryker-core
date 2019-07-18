<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MultiCartDataImport\Business\CartImportStep;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MultiCartDataImport\Business\DataSet\CartDataSetInterface;

class CustomerReferenceValidationStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $customerReferenceCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $customerReference = $dataSet[CartDataSetInterface::CUSTOMER_REFERENCE];
        if (!isset($this->customerReferenceCache[$customerReference])) {
            $customerQuery = SpyCustomerQuery::create();
            $idCustomer = $customerQuery
                ->select(SpyCustomerTableMap::COL_ID_CUSTOMER)
                ->findOneByCustomerReference($customerReference);

            if (!$idCustomer) {
                throw new EntityNotFoundException(sprintf('Could not find customer by reference "%s"', $customerReference));
            }

            $this->customerReferenceCache[$customerReference] = $idCustomer;
        }
    }
}
