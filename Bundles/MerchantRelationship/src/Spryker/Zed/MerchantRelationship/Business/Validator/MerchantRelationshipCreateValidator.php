<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;

class MerchantRelationshipCreateValidator implements MerchantRelationshipValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface>
     */
    protected $merchantRelationshipValidatorRules;

    /**
     * @var array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipCreateValidatorPluginInterface>
     */
    protected $merchantRelationshipCreateValidatorPlugins;

    /**
     * @param array<\Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface> $merchantRelationshipValidatorRules
     * @param array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipCreateValidatorPluginInterface> $merchantRelationshipCreateValidatorPlugins
     */
    public function __construct(array $merchantRelationshipValidatorRules, array $merchantRelationshipCreateValidatorPlugins)
    {
        $this->merchantRelationshipValidatorRules = $merchantRelationshipValidatorRules;
        $this->merchantRelationshipCreateValidatorPlugins = $merchantRelationshipCreateValidatorPlugins;
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

        return $this->executeMerchantRelationshipCreateValidatorPlugins(
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
    protected function executeMerchantRelationshipCreateValidatorPlugins(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
    ): MerchantRelationshipValidationErrorCollectionTransfer {
        foreach ($this->merchantRelationshipCreateValidatorPlugins as $merchantRelationshipCreateValidatorPlugin) {
            $merchantRelationshipValidationErrorCollectionTransfer = $merchantRelationshipCreateValidatorPlugin->validate(
                $merchantRelationshipTransfer,
                $merchantRelationshipValidationErrorCollectionTransfer,
            );
        }

        return $merchantRelationshipValidationErrorCollectionTransfer;
    }
}
