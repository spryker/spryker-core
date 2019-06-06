<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CommentDataImport\Business\CommentDataImportStep;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\CommentDataImport\Business\DataSet\CommentDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CustomerReferenceToIdCustomerStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCustomerCache;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $customerReference = $dataSet[CommentDataSetInterface::COLUMN_CUSTOMER_REFERENCE];

        if (!isset($this->idCustomerCache[$customerReference])) {
            $idCustomer = $this->createCustomerQuery()
                ->select([SpyCustomerTableMap::COL_ID_CUSTOMER])
                ->findOneByCustomerReference($customerReference);

            if (!$idCustomer) {
                throw new EntityNotFoundException(sprintf('Could not find customer by reference "%s"', $customerReference));
            }

            $this->idCustomerCache[$customerReference] = $idCustomer;
        }

        $dataSet[CommentDataSetInterface::ID_CUSTOMER] = $this->idCustomerCache[$customerReference];
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function createCustomerQuery(): SpyCustomerQuery
    {
        return SpyCustomerQuery::create();
    }
}
