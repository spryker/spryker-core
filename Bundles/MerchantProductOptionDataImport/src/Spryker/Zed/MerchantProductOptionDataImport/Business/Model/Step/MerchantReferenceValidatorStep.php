<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOptionDataImport\Business\Model\Step;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOptionDataImport\Business\Model\DataSet\MerchantProductOptionDataSetInterface;

class MerchantReferenceValidatorStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantReference = $dataSet[MerchantProductOptionDataSetInterface::MERCHANT_REFERENCE];

        if (!$merchantReference) {
            throw new InvalidDataException('"' . MerchantProductOptionDataSetInterface::MERCHANT_REFERENCE . '" is a required field');
        }

        /** @var \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery */
        $merchantQuery = SpyMerchantQuery::create();
        $merchantQuery->filterByMerchantReference($merchantReference);

        if ($merchantQuery->count() < 1) {
            throw new EntityNotFoundException(sprintf('Merchant with merchant reference "%s" not found.', $merchantReference));
        }
    }
}
