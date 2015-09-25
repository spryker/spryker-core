<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Symfony\Component\Form\AbstractType;

abstract class AbstractRuleType extends AbstractType
{

    const DECISION_RULES_PREFIX = 'PLUGIN_DECISION_RULE_';
    const DECISION_COLLECTOR_PREFIX = 'PLUGIN_COLLECTOR_';

    /**
     * @param string $decisionRuleName
     *
     * @return string
     */
    protected function filterChoicesLabels($decisionRuleName)
    {
        $decisionRuleName = str_replace(
            [self::DECISION_RULES_PREFIX, self::DECISION_COLLECTOR_PREFIX, '_'],
            ['', '', ' '],
            $decisionRuleName
        );

        return mb_convert_case($decisionRuleName, MB_CASE_TITLE, "UTF-8");
    }
}
