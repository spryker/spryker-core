<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\Step;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\DataSet\MerchantProductDataSetInterface;

class MerchantReferenceToIdMerchantStep implements DataImportStepInterface
{
    protected const MERCHANT_REFERENCE = MerchantProductDataSetInterface::MERCHANT_REFERENCE;

    /**
     * @var array<int>
     */
    protected $idMerchantCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantReference = $dataSet[static::MERCHANT_REFERENCE];

        if (!$merchantReference) {
            throw new InvalidDataException(sprintf('"%s" is required.', static::MERCHANT_REFERENCE));
        }

        $dataSet[MerchantProductDataSetInterface::FK_MERCHANT] = $this->getIdMerchant($merchantReference);
    }

    /**
     * @param string $merchantReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchant(string $merchantReference): int
    {
        if (isset($this->idMerchantCache[$merchantReference])) {
            return $this->idMerchantCache[$merchantReference];
        }

        /** @var \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery */
        $merchantQuery = SpyMerchantQuery::create()
            ->select(SpyMerchantTableMap::COL_ID_MERCHANT);
        /** @var int $idMerchant */
        $idMerchant = $merchantQuery->findOneByMerchantReference($merchantReference);

        if (!$idMerchant) {
            throw new EntityNotFoundException(sprintf('Could not find Merchant by reference "%s"', $merchantReference));
        }

        $this->idMerchantCache[$merchantReference] = $idMerchant;

        return $this->idMerchantCache[$merchantReference];
    }
}
