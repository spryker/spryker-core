<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

class DynamicEntityConfigurationValidator implements DynamicEntityConfigurationValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface>
     */
    protected array $definitionValidatorRules;

    /**
     * @var array<\Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface>
     */
    protected array $configurationValidatorRules;

    /**
     * @param array<\Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface> $configurationValidatorRules
     * @param array<\Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface> $definitionValidatorRules
     */
    public function __construct(array $configurationValidatorRules, array $definitionValidatorRules)
    {
        $this->configurationValidatorRules = $configurationValidatorRules;
        $this->definitionValidatorRules = $definitionValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function validate(
        DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        $dynamicEntityConfigurationCollectionResponseTransfer = $this->validateByRules(
            $dynamicEntityConfigurationCollectionResponseTransfer,
            $this->configurationValidatorRules,
        );

        return $this->validateByRules(
            $dynamicEntityConfigurationCollectionResponseTransfer,
            $this->definitionValidatorRules,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
     * @param array<\Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface> $validatorRules
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    protected function validateByRules(
        DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer,
        array $validatorRules
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        foreach ($validatorRules as $validatorRule) {
            $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionResponseTransfer->getDynamicEntityConfigurations());

            $dynamicEntityConfigurationCollectionResponseTransfer = $this->mergeErrors(
                $dynamicEntityConfigurationCollectionResponseTransfer,
                $errorCollectionTransfer,
            );
        }

        return $dynamicEntityConfigurationCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    protected function mergeErrors(
        DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $dynamicEntityConfigurationCollectionResponseErrorTransfer */
        $dynamicEntityConfigurationCollectionResponseErrorTransfer = $dynamicEntityConfigurationCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $dynamicEntityConfigurationCollectionResponseErrorTransfer->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $dynamicEntityConfigurationCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
