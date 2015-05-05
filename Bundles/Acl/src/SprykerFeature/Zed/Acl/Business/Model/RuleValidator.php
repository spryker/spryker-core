<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\AclRuleTransfer;
use SprykerFeature\Zed\Acl\AclConfig;

class RuleValidator implements RuleValidatorInterface
{
    /**
     * @var array
     */
    protected $allowedRules = [];

    /**
     * @var array
     */
    protected $deniedRules = [];

    /**
     * @param array $rules
     * @param bool $condition
     *
     * @return bool
     */
    protected function validate(array $rules, $condition)
    {
        $count = 0;
        $total = count($rules);
        foreach ($rules as $value) {
            if ($value === $condition) {
                $count++;
            }
        }

        return $count === $total;
    }

    /**
     * @param array $rules
     * @param bool $condition
     *
     * @return array
     */
    protected function reset(array $rules, $condition)
    {
        $result = [];

        foreach ($rules as $key => $value) {
            $result[$key] = $condition;
        }

        return $result;
    }

    /**
     * @param AclRuleTransfer $rules
     *
     * @return $this
     */
    public function setRules(AclRuleTransfer $rules)
    {
        foreach ($rules as $rule) {
            if ($rule->getType() === 'allow') {
                $this->addAllowedRule($rule);
            }
        }

        foreach ($rules as $rule) {
            if ($rule->getType() === 'deny') {
                $this->addDeniedRule($rule);
            }
        }

        return $this;
    }

    /**
     * @param AclRuleTransfer $rule
     */
    public function addRule(AclRuleTransfer $rule)
    {
        switch ($rule->getType()) {
            case 'allow':
                $this->addAllowedRule($rule);
                break;

            case 'deny':
                $this->addDeniedRule($rule);
                break;
        }
    }

    /**
     * @param AclRuleTransfer $rule
     *
     * @return int
     */
    protected function addAllowedRule(AclRuleTransfer $rule)
    {
        return array_push($this->allowedRules, $rule);
    }

    /**
     * @param AclRuleTransfer $rule
     *
     * @return int
     */
    protected function addDeniedRule(AclRuleTransfer $rule)
    {
        return array_push($this->deniedRules, $rule);
    }

    /**
     * @return array
     */
    public function getAllowedRules()
    {
        return $this->allowedRules;
    }

    /**
     * @return array
     */
    public function getDeniedRules()
    {
        return $this->deniedRules;
    }

    /**
     * @param AclRuleTransfer $rule
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public function assert(AclRuleTransfer $rule, $bundle, $controller, $action)
    {
        if (($rule->getBundle() === $bundle || $rule->getBundle() === AclConfig::VALIDATOR_WILDCARD) &&
            ($rule->getController() === $controller || $rule->getController() === AclConfig::VALIDATOR_WILDCARD) &&
            ($rule->getAction() === $action || $rule->getAction() === AclConfig::VALIDATOR_WILDCARD)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAccessible($bundle, $controller, $action)
    {
        foreach ($this->getDeniedRules() as $rule) {
            if ($this->assert($rule, $bundle, $controller, $action)) {
                return false;
            }
        }

        foreach ($this->getAllowedRules() as $rule) {
            if ($this->assert($rule, $bundle, $controller, $action)) {
                return true;
            }
        }

        return false;
    }
}
