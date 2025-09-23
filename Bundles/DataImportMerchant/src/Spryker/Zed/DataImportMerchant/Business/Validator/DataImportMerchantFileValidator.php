<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Validator;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\DataImportMerchantFileValidatorRuleInterface;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class DataImportMerchantFileValidator implements DataImportMerchantFileValidatorInterface
{
    /**
     * @param list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface> $dataImportMerchantFileValidatorPlugins
     * @param list<\Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\DataImportMerchantFileValidatorRuleInterface> $dataImportMerchantFileValidatorRules
     */
    public function __construct(
        protected array $dataImportMerchantFileValidatorPlugins,
        protected array $dataImportMerchantFileValidatorRules
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function validate(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        $dataImportMerchantFileCollectionResponseTransfer = $this->executeDataImportMerchantFileValidatorRules($dataImportMerchantFileCollectionResponseTransfer);

        if ($dataImportMerchantFileCollectionResponseTransfer->getErrors()->count() > 0) {
            return $dataImportMerchantFileCollectionResponseTransfer;
        }

        return $this->executeDataImportMerchantFileValidatorPlugins($dataImportMerchantFileCollectionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    protected function executeDataImportMerchantFileValidatorRules(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        foreach ($this->dataImportMerchantFileValidatorRules as $dataImportMerchantFileValidatorRule) {
            $initialErrorsCount = $dataImportMerchantFileCollectionResponseTransfer->getErrors()->count();
            $dataImportMerchantFileCollectionResponseTransfer = $dataImportMerchantFileValidatorRule->validate($dataImportMerchantFileCollectionResponseTransfer);
            $postValidationErrorsCount = $dataImportMerchantFileCollectionResponseTransfer->getErrors()->count();

            if ($this->isValidationTerminated($dataImportMerchantFileValidatorRule, $initialErrorsCount, $postValidationErrorsCount)) {
                break;
            }
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\DataImportMerchantFileValidatorRuleInterface $validatorRule
     * @param int $initialErrorsCount
     * @param int $postValidationErrorsCount
     *
     * @return bool
     */
    protected function isValidationTerminated(
        DataImportMerchantFileValidatorRuleInterface $validatorRule,
        int $initialErrorsCount,
        int $postValidationErrorsCount
    ): bool {
        if (!$validatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $initialErrorsCount !== $postValidationErrorsCount;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    protected function executeDataImportMerchantFileValidatorPlugins(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        foreach ($this->dataImportMerchantFileValidatorPlugins as $dataImportMerchantFileValidatorPlugin) {
            $dataImportMerchantFileCollectionResponseTransfer = $dataImportMerchantFileValidatorPlugin->validate($dataImportMerchantFileCollectionResponseTransfer);
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }
}
