<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;

/**
 * Implement this interface to create a provider for a specific rule specification.
 */
interface RuleSpecificationProviderPluginInterface
{
    /**
     * Specification:
     * - Returns the domain name of the specification provider.
     *
     * @api
     *
     * @return string
     */
    public function getDomainName(): string;

    /**
     * Specification:
     * - Returns the specification type provided by the specification provider.
     *
     * @api
     *
     * @return string
     */
    public function getSpecificationType(): string;

    /**
     * Specification:
     * - Returns the rule specification context for given clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function getRuleSpecificationContext(RuleEngineClauseTransfer $ruleEngineClauseTransfer): RuleSpecificationInterface;

    /**
     * Specification:
     * - Returns a rule specification where both nodes should satisfy the given clause.
     *
     * @api
     *
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function createAnd(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface;

    /**
     * Specification:
     * - Returns a rule specification where at least one of nodes should satisfy the given clause.
     *
     * @api
     *
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function createOr(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface;

    /**
     * Specification:
     * - Returns a stack of {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface} used by this Specification Provider.
     *
     * @api
     *
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface>
     */
    public function getRulePlugins(): array;
}
