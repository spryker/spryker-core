<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\Validator;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class MerchantCombinedProductValidator implements MerchantCombinedProductValidatorInterface
{
    /**
     * @var list<string>
     */
    protected const REQUIRED_HEADERS = [
        'abstract_sku',
        'product.assigned_product_type',
    ];

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MISSING_REQUIRED_HEADER = 'merchant_product_data_import.validation.missing_required_header';

    /**
     * @var string
     */
    protected const PARAM_HEADER = '%header%';

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function validateDataImportMerchantFileCollection(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $entityIdentifier => $dataImportMerchantFileTransfer) {
            if ($dataImportMerchantFileTransfer->getImporterTypeOrFail() !== MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT) {
                continue;
            }

            [$rawHeaders] = explode(PHP_EOL, $dataImportMerchantFileTransfer->getFileInfoOrFail()->getContentOrFail());
            $headers = str_getcsv($rawHeaders);

            foreach (static::REQUIRED_HEADERS as $requiredHeader) {
                if (!in_array($requiredHeader, $headers, true)) {
                    $errorTransfer = (new ErrorTransfer())
                        ->setMessage(static::GLOSSARY_KEY_MISSING_REQUIRED_HEADER)
                        ->setParameters([static::PARAM_HEADER => $requiredHeader])
                        ->setEntityIdentifier((string)$entityIdentifier);

                    $dataImportMerchantFileCollectionResponseTransfer->addError($errorTransfer);
                }
            }
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }
}
