<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;

class MerchantRelationshipUpdateValidator implements MerchantRelationshipValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface>
     */
    protected $merchantRelationshipValidatorRules;

    /**
     * @var array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipUpdateValidatorPluginInterface>
     */
    protected $merchantRelationshipUpdateValidatorPlugins;

    /**
     * @param array<\Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface> $merchantRelationshipValidatorRules
     * @param array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipUpdateValidatorPluginInterface> $merchantRelationshipUpdateValidatorPlugins
     */
    public function __construct(array $merchantRelationshipValidatorRules, array $merchantRelationshipUpdateValidatorPlugins)
    {
        $this->merchantRelationshipValidatorRules = $merchantRelationshipValidatorRules;
        $this->merchantRelationshipUpdateValidatorPlugins = $merchantRelationshipUpdateValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer
     */
    public function validate(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
    ): MerchantRelationshipValidationErrorCollectionTransfer {
        foreach ($this->merchantRelationshipValidatorRules as $merchantRelationshipValidatorRule) {
            $merchantRelationshipValidationErrorCollectionTransfer = $merchantRelationshipValidatorRule->validate(
                $merchantRelationshipTransfer,
                $merchantRelationshipValidationErrorCollectionTransfer,
            );
        }

        return $this->executeMerchantRelationshipUpdateValidatorPlugins(
            $merchantRelationshipTransfer,
            $merchantRelationshipValidationErrorCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer
     */
    protected function executeMerchantRelationshipUpdateValidatorPlugins(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
    ): MerchantRelationshipValidationErrorCollectionTransfer {
        foreach ($this->merchantRelationshipUpdateValidatorPlugins as $merchantRelationshipUpdateValidatorPlugin) {
            $merchantRelationshipValidationErrorCollectionTransfer = $merchantRelationshipUpdateValidatorPlugin->validate(
                $merchantRelationshipTransfer,
                $merchantRelationshipValidationErrorCollectionTransfer,
            );
        }

        return $merchantRelationshipValidationErrorCollectionTransfer;
    }
}
