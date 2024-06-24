<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin;

/**
 * Implement this interface to create a rule plugin with an expanded list of available values.
 */
interface RuleWithAttributesPluginInterface
{
    /**
     * Specification:
     * - Special rule type which allows to expand available value list for decision or collector.
     * - For example product attributes. Uses one decision rules where available attributes will be provided by `getAttributeTypes`.
     * - So you can filter by `attribute.color` = 'red' or `attribute.brand` = 'nike'. With same decision rule.
     *
     * @api
     *
     * @return list<string>
     */
    public function getAttributeTypes(): array;
}
