<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class RuleEngineConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Data type that represents int and float values.
     * - Used by compare operators.
     *
     * @api
     *
     * @var string
     */
    public const DATA_TYPE_NUMBER = 'number';

    /**
     * Specification:
     * - Data type that represents string values.
     * - Used by compare operators.
     *
     * @api
     *
     * @var string
     */
    public const DATA_TYPE_STRING = 'string';

    /**
     * Specification:
     * - Data type that represents a list of scalar values.
     * - Used by compare operators.
     *
     * @api
     *
     * @var string
     */
    public const DATA_TYPE_LIST = 'list';

    /**
     * Specification:
     * - Symbol used to separate list items.
     *
     * @api
     *
     * @var string
     */
    public const LIST_DELIMITER = ';';

    /**
     * @var string
     */
    protected const COLLECTOR_RULE_SPECIFICATION_TYPE = 'collector';

    /**
     * @var string
     */
    protected const DECISION_RULE_SPECIFICATION_TYPE = 'decision';

    /**
     * Specification:
     * - Returns the type of the collector rule specification.
     * - Collector rule specifications are used to collect items from the collection that satisfy the collector's query.
     *
     * @api
     *
     * @return string
     */
    public function getCollectorRuleSpecificationType(): string
    {
        return static::COLLECTOR_RULE_SPECIFICATION_TYPE;
    }

    /**
     * Specification:
     * - Returns the type of the decision rule specification.
     * - Decision rule specifications are used to evaluate if the provided item(s) satisfies the decision rule query.
     *
     * @api
     *
     * @return string
     */
    public function getDecisionRuleSpecificationType(): string
    {
        return static::DECISION_RULE_SPECIFICATION_TYPE;
    }
}
