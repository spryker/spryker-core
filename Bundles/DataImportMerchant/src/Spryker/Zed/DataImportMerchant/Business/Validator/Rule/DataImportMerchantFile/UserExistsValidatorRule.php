<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImportMerchant\Business\Reader\UserReaderInterface;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class UserExistsValidatorRule implements DataImportMerchantFileValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND = 'data_import_merchant.validation.user_not_found';

    /**
     * @param \Spryker\Zed\DataImportMerchant\Business\Reader\UserReaderInterface $userReader
     */
    public function __construct(protected UserReaderInterface $userReader)
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
        $userIds = $this->extractUserIds($dataImportMerchantFileCollectionResponseTransfer);
        $existingUserIds = $this->getExistingUserIds($userIds);
        $index = 0;

        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            if (!in_array($dataImportMerchantFileTransfer->getIdUser(), $existingUserIds, true)) {
                $entityIdentifier = $dataImportMerchantFileTransfer->getUuid() ?? (string)$index;
                $errorTransfer = (new ErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND)
                    ->setEntityIdentifier($entityIdentifier);

                $dataImportMerchantFileCollectionResponseTransfer->addError($errorTransfer);
            }
            $index++;
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return list<int>
     */
    protected function extractUserIds(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): array {
        $userIds = [];
        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $userIds[] = $dataImportMerchantFileTransfer->getIdUserOrFail();
        }

        return array_unique($userIds);
    }

    /**
     * @param list<int> $userIds
     *
     * @return list<int>
     */
    protected function getExistingUserIds(array $userIds): array
    {
        if (!$userIds) {
            return [];
        }

        $userCollectionTransfer = $this->userReader->getUserCollectionByUserIds($userIds);
        $existingUserIds = [];

        foreach ($userCollectionTransfer->getUsers() as $userTransfer) {
            $existingUserIds[] = $userTransfer->getIdUserOrFail();
        }

        return $existingUserIds;
    }
}
