<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Resolver;

use Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer;
use Spryker\Zed\RuleEngine\Business\Exception\RuleSpecificationProviderPluginNotFoundException;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;

class RuleSpecificationProviderResolver implements RuleSpecificationProviderResolverInterface
{
    /**
     * @var list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface>
     */
    protected array $ruleSpecificationProviderPlugins;

    /**
     * @param list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface> $ruleSpecificationProviderPlugins
     */
    public function __construct(array $ruleSpecificationProviderPlugins)
    {
        $this->ruleSpecificationProviderPlugins = $ruleSpecificationProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer $ruleEngineSpecificationProviderRequestTransfer
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\RuleSpecificationProviderPluginNotFoundException
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface
     */
    public function resolveRuleSpecificationProviderPlugin(
        RuleEngineSpecificationProviderRequestTransfer $ruleEngineSpecificationProviderRequestTransfer
    ): RuleSpecificationProviderPluginInterface {
        foreach ($this->ruleSpecificationProviderPlugins as $specificationProviderPlugin) {
            if ($this->isPluginApplicable($specificationProviderPlugin, $ruleEngineSpecificationProviderRequestTransfer)) {
                return $specificationProviderPlugin;
            }
        }

        throw new RuleSpecificationProviderPluginNotFoundException(
            sprintf(
                'Rule specification provider plugin for domain name "%s" and specification rule type "%s" not found.',
                $ruleEngineSpecificationProviderRequestTransfer->getDomainNameOrFail(),
                $ruleEngineSpecificationProviderRequestTransfer->getSpecificationRuleTypeOrFail(),
            ),
        );
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface $specificationProviderPlugin
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer $ruleEngineSpecificationProviderRequestTransfer
     *
     * @return bool
     */
    protected function isPluginApplicable(
        RuleSpecificationProviderPluginInterface $specificationProviderPlugin,
        RuleEngineSpecificationProviderRequestTransfer $ruleEngineSpecificationProviderRequestTransfer
    ): bool {
        return $specificationProviderPlugin->getDomainName() === $ruleEngineSpecificationProviderRequestTransfer->getDomainNameOrFail() &&
            $specificationProviderPlugin->getSpecificationType() === $ruleEngineSpecificationProviderRequestTransfer->getSpecificationRuleTypeOrFail();
    }
}
