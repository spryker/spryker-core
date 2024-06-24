<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin;

/**
 * Implement this interface if you want to add a custom rule.
 */
interface RulePluginInterface
{
    /**
     * Specification:
     * - Name of the field as used in query string.
     *
     * @api
     *
     * @return string
     */
    public function getFieldName(): string;

    /**
     * Specification:
     * - Data types used by this field (string, number, list).
     *
     * @api
     *
     * @return list<string>
     */
    public function acceptedDataTypes(): array;
}
