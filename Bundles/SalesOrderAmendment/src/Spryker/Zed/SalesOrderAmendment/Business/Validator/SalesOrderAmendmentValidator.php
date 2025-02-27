<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Validator;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentValidatorRuleInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\TerminationAwareValidatorRuleInterface;

class SalesOrderAmendmentValidator implements SalesOrderAmendmentValidatorInterface
{
    /**
     * @param list<\Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentValidatorRuleInterface> $salesOrderAmendmentValidatorRules
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface> $salesOrderAmendmentValidatorRulePlugins
     */
    public function __construct(protected array $salesOrderAmendmentValidatorRules, protected array $salesOrderAmendmentValidatorRulePlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer
     */
    public function validate(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): SalesOrderAmendmentResponseTransfer
    {
        $salesOrderAmendmentResponseTransfer = (new SalesOrderAmendmentResponseTransfer())
            ->setSalesOrderAmendment($salesOrderAmendmentTransfer);

        foreach ($this->salesOrderAmendmentValidatorRules as $salesOrderAmendmentValidatorRule) {
            $errorCollectionTransfer = $salesOrderAmendmentValidatorRule->validate($salesOrderAmendmentTransfer);
            $salesOrderAmendmentResponseTransfer = $this->mergeErrors(
                $salesOrderAmendmentResponseTransfer,
                $errorCollectionTransfer,
            );

            if ($this->isValidationTerminated($salesOrderAmendmentValidatorRule, $errorCollectionTransfer)) {
                break;
            }
        }

        return $this->executeSalesOrderAmendmentValidatorRulePlugins(
            $salesOrderAmendmentTransfer,
            $salesOrderAmendmentResponseTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer $salesOrderAmendmentResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer
     */
    protected function executeSalesOrderAmendmentValidatorRulePlugins(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer,
        SalesOrderAmendmentResponseTransfer $salesOrderAmendmentResponseTransfer
    ): SalesOrderAmendmentResponseTransfer {
        foreach ($this->salesOrderAmendmentValidatorRulePlugins as $salesOrderAmendmentValidatorRulePlugin) {
            $errorCollectionTransfer = $salesOrderAmendmentValidatorRulePlugin->validate($salesOrderAmendmentTransfer);
            $salesOrderAmendmentResponseTransfer = $this->mergeErrors(
                $salesOrderAmendmentResponseTransfer,
                $errorCollectionTransfer,
            );
        }

        return $salesOrderAmendmentResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentValidatorRuleInterface $validatorRule
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return bool
     */
    protected function isValidationTerminated(
        SalesOrderAmendmentValidatorRuleInterface $validatorRule,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): bool {
        if (!$validatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $errorCollectionTransfer->getErrors()->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer $salesOrderAmendmentResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer
     */
    protected function mergeErrors(
        SalesOrderAmendmentResponseTransfer $salesOrderAmendmentResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): SalesOrderAmendmentResponseTransfer {
        $errorTransfers = $salesOrderAmendmentResponseTransfer->getErrors();
        foreach ($errorCollectionTransfer->getErrors() as $errorTransfer) {
            $errorTransfers->append($errorTransfer);
        }

        return $salesOrderAmendmentResponseTransfer->setErrors($errorTransfers);
    }
}
