<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\MerchantCommissionValidatorRuleInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class MerchantCommissionValidator implements MerchantCommissionValidatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface
     */
    protected ErrorExtractorInterface $errorExtractor;

    /**
     * @var list<\Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\MerchantCommissionValidatorRuleInterface>
     */
    protected array $validatorRules;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface $errorExtractor
     * @param list<\Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\MerchantCommissionValidatorRuleInterface> $validatorRules
     */
    public function __construct(ErrorExtractorInterface $errorExtractor, array $validatorRules)
    {
        $this->errorExtractor = $errorExtractor;
        $this->validatorRules = $validatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function validate(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer {
        $merchantCommissionCollectionResponseTransfer = (new MerchantCommissionCollectionResponseTransfer())
            ->setMerchantCommissions($merchantCommissionCollectionRequestTransfer->getMerchantCommissions());

        $merchantCommissionTransfers = $merchantCommissionCollectionRequestTransfer->getMerchantCommissions();
        foreach ($this->validatorRules as $validatorRule) {
            $errorCollectionTransfer = $validatorRule->validate($merchantCommissionTransfers);

            $merchantCommissionCollectionResponseTransfer = $this->mergeErrors(
                $merchantCommissionCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            $isValidationTerminated = $this->isValidationTerminated($validatorRule, $errorCollectionTransfer->getErrors());
            if ($isValidationTerminated && $merchantCommissionCollectionRequestTransfer->getIsTransactionalOrFail()) {
                break;
            }

            if ($isValidationTerminated) {
                $merchantCommissionTransfers = $this->filterOutInvalidMerchantCommissions(
                    $merchantCommissionTransfers,
                    $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorCollectionTransfer->getErrors()),
                );
            }
        }

        return $merchantCommissionCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission\MerchantCommissionValidatorRuleInterface $validatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        MerchantCommissionValidatorRuleInterface $validatorRule,
        ArrayObject $errorTransfers
    ): bool {
        if (!$validatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return (bool)$errorTransfers->count();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    protected function mergeErrors(
        MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): MerchantCommissionCollectionResponseTransfer {
        $errorTransfers = $merchantCommissionCollectionResponseTransfer->getErrors();
        foreach ($errorCollectionTransfer->getErrors() as $errorTransfer) {
            $errorTransfers->append($errorTransfer);
        }

        return $merchantCommissionCollectionResponseTransfer->setErrors($errorTransfers);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     * @param array<string, string> $invalidEntityIdentifiers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    protected function filterOutInvalidMerchantCommissions(
        ArrayObject $merchantCommissionTransfers,
        array $invalidEntityIdentifiers
    ): ArrayObject {
        $filteredMerchantCommissionTransfers = new ArrayObject();
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            if (isset($invalidEntityIdentifiers[$entityIdentifier])) {
                continue;
            }

            $filteredMerchantCommissionTransfers->offsetSet($entityIdentifier, $merchantCommissionTransfer);
        }

        return $filteredMerchantCommissionTransfers;
    }
}
