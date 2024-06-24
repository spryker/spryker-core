<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationResponseTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface RuleEngineFacadeInterface
{
    /**
     * Specification:
     * - Requires `RuleEngineSpecificationRequestTransfer.queryString` to be set.
     * - Requires `RuleEngineSpecificationRequestTransfer.ruleEngineSpecificationProviderRequest` to be set.
     * - Requires `RuleEngineSpecificationProviderRequestTransfer.domainName` to be set.
     * - Finds applicable {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface} by provided `RuleEngineSpecificationRequestTransfer.domainName`.
     * - Builds {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface} by provided `RuleEngineSpecificationRequestTransfer`.
     * - Executes built collector rule specification for provided collectable transfer.
     * - Returns all collected items from collectable transfer that satisfy provided `RuleEngineSpecificationRequestTransfer.queryString`.
     *
     * @api
     *
     * @example
     * // Define some items
     * $items = new \ArrayObject([
     *     ['type' => 'book', 'title' => '1984'],
     *     ['type' => 'book', 'title' => 'Brave New World'],
     *     ['type' => 'magazine', 'title' => 'Time'],
     *     ['type' => 'magazine', 'title' => 'National Geographic']
     * ]);
     *
     * // Create a `CollectableTransfer` object with the items
     * $collectableTransfer = (new CollectableTransfer())->setItems($items);
     *
     * // Implement a `CollectorRuleSpecificationProviderPlugin` implementing {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface}.
     * // Add a `CollectorRuleSpecificationProviderPlugin` to {@link \Spryker\Zed\RuleEngine\RuleEngineDependencyProvider::getRuleSpecificationProviderPlugins()}.
     * // Implement `CollectorRulePlugin` implementing {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface}.
     * // `CollectorRulePlugin` should return items that satisfy `RuleEngineClauseTransfer`.
     * // Make sure this plugin is returned by the `CollectorRuleSpecificationProviderPlugin::getRulePlugins()`.
     *
     * // Create a `RuleEngineSpecificationRequestTransfer` object with the query string and domain name specified in the `CollectorRuleSpecificationProviderPlugin`.
     * $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestTransfer())
     *      ->setQueryString('type = "book"')
     *      ->setRuleEngineSpecificationProviderRequest(
     *          (new RuleEngineSpecificationProviderRequestTransfer())->setDomainName(CollectorRuleSpecificationProviderPlugin->getDomainName()),
     *      );
     *
     * // Pass `CollectableTransfer` and `RuleEngineSpecificationRequestTransfer` to the `RuleEngineFacade::collect()` method.
     * $collectedItems = (new RuleEngineFacade())->collect($collectableTransfer, $ruleEngineSpecificationRequestTransfer);
     *
     * // The $collectedItems will contain only items with type "book":
     * // ['type' => 'book', 'title' => '1984'],
     * // ['type' => 'book', 'title' => 'Brave New World'],
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(
        TransferInterface $collectableTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): array;

    /**
     * Specification:
     * - Requires `RuleEngineSpecificationRequestTransfer.queryString` to be set.
     * - Requires `RuleEngineSpecificationRequestTransfer.ruleEngineSpecificationProviderRequest` to be set.
     * - Requires `RuleEngineSpecificationProviderRequestTransfer.domainName` to be set.
     * - Finds applicable {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface} by provided `RuleEngineSpecificationRequestTransfer.domainName`.
     * - Builds {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface} by provided `RuleEngineSpecificationRequestTransfer`.
     * - Executes built decision rule specification for provided collectable transfer.
     * - Returns `true` if satisfying transfer satisfies provided `RuleEngineSpecificationRequestTransfer.queryString`.
     *
     * @api
     *
     * @example
     * // Define some transfer object with properties you want to compare to the decision rule query string.
     * $comparableTransfer = (new ComparableTransfer())->setProperty('value1');
     *
     * // Implement a `DecisionRuleSpecificationProviderPlugin` implementing {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface}.
     * // Add a `DecisionRuleSpecificationProviderPlugin` to {@link \Spryker\Zed\RuleEngine\RuleEngineDependencyProvider::getRuleSpecificationProviderPlugins()}.
     * // Implement `DecisionRulePlugin` implementing {@link \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface}.
     * // `DecisionRulePlugin` should check if `ComparableTransfer.property` value satisfies `RuleEngineClauseTransfer`.
     * // Make sure this plugin is returned by the `DecisionRuleSpecificationProviderPlugin::getRulePlugins()`.
     *
     * // Create a `RuleEngineSpecificationRequestTransfer` object with the query string and domain name specified in the `DecisionRuleSpecificationProviderPlugin`.
     * $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestTransfer())
     *      ->setQueryString('property IS IN "value1;value2;value3"')
     *      ->setRuleEngineSpecificationProviderRequest(
     *          (new RuleEngineSpecificationProviderRequestTransfer())->setDomainName((new DecisionRuleSpecificationProviderPlugin())->getDomainName()),
     *      );
     *
     * // Pass `ComparableTransfer` and `RuleEngineSpecificationRequestTransfer` to the `RuleEngineFacade::isSatisfiedBy()` method.
     * $isSatisfiedBy = (new RuleEngineFacade())->isSatisfiedBy($comparableTransfer, $ruleEngineSpecificationRequestTransfer);
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        TransferInterface $satisfyingTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): bool;

    /**
     * Specification:
     * - Requires `RuleEngineClauseTransfer.operator` to be set.
     * - Requires `RuleEngineClauseTransfer.value` to be set.
     * - Selects compare operator based on `RuleEngineClauseTransfer.operator`.
     * - Compares `RuleEngineClauseTransfer.value` with provided `$comparedValue`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $comparedValue
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, mixed $comparedValue): bool;

    /**
     * Specification:
     * - Requires `RuleEngineQueryStringValidationRequestTransfer.ruleEngineSpecificationProviderRequest` to be set.
     * - Requires `RuleEngineSpecificationProviderRequestTransfer.domainName` to be set.
     * - Requires `RuleEngineSpecificationProviderRequestTransfer.specificationRuleType` to be set.
     * - Validates if provided query strings have correct structure.
     * - Validates if provided query strings contain correct fields for given domain name and specification rule type.
     * - Returns `QueryStringValidationResponseTransfer` with found errors if any.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer $ruleEngineQueryStringValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RuleEngineQueryStringValidationResponseTransfer
     */
    public function validateQueryString(
        RuleEngineQueryStringValidationRequestTransfer $ruleEngineQueryStringValidationRequestTransfer
    ): RuleEngineQueryStringValidationResponseTransfer;
}
