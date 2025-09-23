<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\DataImportMerchant\DataImportMerchantConfig;

class FileContentTypeValidatorRule implements DataImportMerchantFileValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_INVALID_FILE_CONTENT_TYPE = 'data_import_merchant.validation.invalid_file_content_type';

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
        $index = 0;
        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            if (!$this->isContentTypeValid($dataImportMerchantFileTransfer)) {
                $entityIdentifier = $dataImportMerchantFileTransfer->getUuid() ?? (string)$index;
                $errorTransfer = (new ErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_VALIDATION_INVALID_FILE_CONTENT_TYPE)
                    ->setEntityIdentifier($entityIdentifier)
                    ->setParameters([
                        '%allowed_extensions%' => implode(', ', $this->dataImportMerchantConfig->getSupportedContentTypes()),
                    ]);

                $dataImportMerchantFileCollectionResponseTransfer->addError($errorTransfer);
            }
            $index++;
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return bool
     */
    protected function isContentTypeValid(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): bool
    {
        $contentType = $dataImportMerchantFileTransfer->getFileInfoOrFail()->getContentTypeOrFail();
        $supportedContentTypes = $this->dataImportMerchantConfig->getSupportedContentTypes();

        return in_array($contentType, $supportedContentTypes, true);
    }
}
