<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * Implement this interface if you want to add a custom collector rule specification.
 */
interface CollectorRuleSpecificationInterface extends RuleSpecificationInterface
{
    /**
     * Specification:
     * - Collects data items from provided collection transfer that satisfy the clause.
     * - Executes {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface::collect()} to get the items.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(TransferInterface $collectableTransfer): array;
}
