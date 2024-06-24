<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * Implement this interface if you want to add a custom decision rule specification.
 */
interface DecisionRuleSpecificationInterface extends RuleSpecificationInterface
{
    /**
     * Specification:
     * - Makes decision if provided transfer satisfies the clause.
     * - Executes {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface::isSatisfiedBy()} to make the decision.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(TransferInterface $satisfyingTransfer): bool;
}
