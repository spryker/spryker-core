<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Resolver;

use Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;

interface RuleSpecificationProviderResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer $ruleEngineSpecificationProviderRequestTransfer
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\RuleSpecificationProviderPluginNotFoundException
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface
     */
    public function resolveRuleSpecificationProviderPlugin(
        RuleEngineSpecificationProviderRequestTransfer $ruleEngineSpecificationProviderRequestTransfer
    ): RuleSpecificationProviderPluginInterface;
}
