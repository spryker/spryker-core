<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\AclRuleTransfer;
use SprykerEngine\Zed\Transfer\Business\Model\TransferArrayObject;

interface RuleValidatorInterface
{
    /**
     * @param TransferArrayObject $rules
     *
     * @return mixed
     */
    public function setRules(TransferArrayObject $rules);

    /**
     * @param AclRuleTransfer $rule
     *
     * @return mixed
     */
    public function addRule(AclRuleTransfer $rule);

    /**
     * @return array
     */
    public function getAllowedRules();

    /**
     * @return array
     */
    public function getDeniedRules();

    /**
     * @param AclRuleTransfer $rule
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function assert(AclRuleTransfer $rule, $bundle, $controller, $action);

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAccessible($bundle, $controller, $action);
}
