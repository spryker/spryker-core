<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Specification\MetaData;

interface MetaDataProviderInterface
{
    /**
     * @param list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface> $rulePlugins
     * @param string $field
     *
     * @return bool
     */
    public function isFieldAvailable(array $rulePlugins, string $field): bool;
}
