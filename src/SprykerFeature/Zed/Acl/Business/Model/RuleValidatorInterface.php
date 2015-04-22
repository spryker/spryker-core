<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use SprykerFeature\Shared\Acl\Transfer\Rule;
use SprykerFeature\Shared\Acl\Transfer\RuleCollection;

interface RuleValidatorInterface
{
    /**
     * @param RuleCollection $rules
     *
     * @return RuleValidator $this
     */
    public function setRules(RuleCollection $rules);

    /**
     * @param Rule $rule
     */
    public function addRule(Rule $rule);

    /**
     * @return array
     */
    public function getAllowedRules();

    /**
     * @return array
     */
    public function getDeniedRules();

    /**
     * @param Rule $rule
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function assert(Rule $rule, $bundle, $controller, $action);

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAccessible($bundle, $controller, $action);
}