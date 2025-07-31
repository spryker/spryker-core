<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\DataImporterConfigurationContextTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImporter;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;

class AddMerchantIdKeyStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_ID_MERCHANT = 'DATA_ID_MERCHANT';

    /**
     * @var string
     */
    protected const ERROR_MISSING_CONTEXT = 'Dataset is missing context transfer.';

    /**
     * @var string
     */
    protected const ERROR_MISSING_MERCHANT_ID = 'Dataset context transfer is missing merchant ID.';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $contextTransfer = $this->getContextTransfer($dataSet);

        if (!$contextTransfer) {
            throw new MerchantCombinedProductException(static::ERROR_MISSING_CONTEXT);
        }

        if (!$contextTransfer->getIdMerchant()) {
            throw new MerchantCombinedProductException(static::ERROR_MISSING_MERCHANT_ID);
        }

        $dataSet[static::KEY_ID_MERCHANT] = $contextTransfer->getIdMerchant();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationContextTransfer|null
     */
    protected function getContextTransfer(DataSetInterface $dataSet): ?DataImporterConfigurationContextTransfer
    {
        return $dataSet[DataImporter::KEY_CONTEXT];
    }
}
