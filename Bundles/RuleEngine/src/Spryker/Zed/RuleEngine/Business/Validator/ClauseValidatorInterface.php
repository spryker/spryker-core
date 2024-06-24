<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Validator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;

interface ClauseValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin
     *
     * @return void
     */
    public function validateClause(
        RuleEngineClauseTransfer $ruleEngineClauseTransfer,
        RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin
    ): void;
}
