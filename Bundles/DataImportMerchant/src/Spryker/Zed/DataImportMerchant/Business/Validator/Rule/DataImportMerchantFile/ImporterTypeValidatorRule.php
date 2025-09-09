<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\DataImportMerchant\DataImportMerchantConfig;

class ImporterTypeValidatorRule implements DataImportMerchantFileValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_IMPORTER_TYPE_NOT_SUPPORTED = 'data_import_merchant.validation.importer_type_not_supported';

    /**
     * @param \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig $dataImportMerchantConfig
     */
    public function __construct(protected DataImportMerchantConfig $dataImportMerchantConfig)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function validate(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        $supportedImporterTypes = $this->dataImportMerchantConfig->getSupportedImporterTypes();
        $index = 0;

        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $importerType = $dataImportMerchantFileTransfer->getImporterType();

            if (!in_array($importerType, $supportedImporterTypes, true)) {
                $entityIdentifier = $dataImportMerchantFileTransfer->getUuid() ?? (string)$index;
                $errorTransfer = (new ErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_VALIDATION_IMPORTER_TYPE_NOT_SUPPORTED)
                    ->setEntityIdentifier($entityIdentifier);

                $dataImportMerchantFileCollectionResponseTransfer->addError($errorTransfer);
            }
            $index++;
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }
}
