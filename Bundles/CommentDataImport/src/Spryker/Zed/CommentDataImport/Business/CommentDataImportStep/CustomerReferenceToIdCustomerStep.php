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
    protected $idCustomerBuffer;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $customerReference = $dataSet[CommentDataSetInterface::COLUMN_CUSTOMER_REFERENCE];

        $dataSet[CommentDataSetInterface::ID_CUSTOMER] = $this->getIdCustomerByReference($customerReference);
    }

    /**
     * @param string $customerReference
     *
     * @return int
     */
    protected function getIdCustomerByReference(string $customerReference): int
    {
        if (isset($this->idCustomerBuffer[$customerReference])) {
            return $this->idCustomerBuffer[$customerReference];
        }

        return $this->resolveCustomerReference($customerReference);
    }

    /**
     * @param string $customerReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function resolveCustomerReference(string $customerReference): int
    {
        /** @var int $idCustomer */
        $idCustomer = $this->createCustomerQuery()
            ->select([SpyCustomerTableMap::COL_ID_CUSTOMER])
            ->findOneByCustomerReference($customerReference);

        if (!$idCustomer) {
            throw new EntityNotFoundException(sprintf('Could not find customer by reference "%s"', $customerReference));
        }

        $this->idCustomerBuffer[$customerReference] = $idCustomer;

        return $idCustomer;
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function createCustomerQuery(): SpyCustomerQuery
    {
        return SpyCustomerQuery::create();
    }
}
