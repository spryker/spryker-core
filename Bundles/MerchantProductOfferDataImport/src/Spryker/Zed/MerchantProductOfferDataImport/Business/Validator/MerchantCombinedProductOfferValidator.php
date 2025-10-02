<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Validator;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;

class MerchantCombinedProductOfferValidator implements MerchantCombinedProductOfferValidatorInterface
{
    /**
     * @var list<string>
     */
    protected const REQUIRED_HEADERS = [
        'offer_reference',
        'concrete_sku',
    ];

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MISSING_REQUIRED_HEADER = 'The required field "%field%" is missing.';

    /**
     * @var string
     */
    protected const PARAM_FIELD = '%field%';

    public function __construct(protected MerchantProductOfferDataImportConfig $merchantProductOfferDataImportConfig)
    {
    }

    public function validateDataImportMerchantFileCollection(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $entityIdentifier => $dataImportMerchantFileTransfer) {
            if ($dataImportMerchantFileTransfer->getImporterTypeOrFail() !== $this->merchantProductOfferDataImportConfig->getImportTypeMerchantCombinedProductOffer()) {
                continue;
            }

            $dataImportMerchantFileCollectionResponseTransfer = $this->validateDataImportMerchantFile(
                $dataImportMerchantFileCollectionResponseTransfer,
                $dataImportMerchantFileTransfer,
                (string)$entityIdentifier,
            );
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }

    protected function validateDataImportMerchantFile(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer,
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer,
        string $entityIdentifier
    ): DataImportMerchantFileCollectionResponseTransfer {
        [$rawHeaders] = explode(PHP_EOL, $dataImportMerchantFileTransfer->getFileInfoOrFail()->getContentOrFail());
        $headers = str_getcsv($rawHeaders);

        foreach (static::REQUIRED_HEADERS as $requiredHeader) {
            if (!in_array($requiredHeader, $headers, true)) {
                $errorTransfer = (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_MISSING_REQUIRED_HEADER)
                    ->setParameters([static::PARAM_FIELD => $requiredHeader])
                    ->setEntityIdentifier($entityIdentifier);

                $dataImportMerchantFileCollectionResponseTransfer->addError($errorTransfer);
            }
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }
}
