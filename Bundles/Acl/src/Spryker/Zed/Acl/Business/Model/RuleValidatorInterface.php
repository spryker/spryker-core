<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RulesTransfer;
use Generated\Shared\Transfer\RuleTransfer;

interface RuleValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RulesTransfer $rules
     *
     * @return $this
     */
    public function setRules(RulesTransfer $rules);

    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $rule
     *
     * @return void
     */
    public function addRule(RuleTransfer $rule);

    /**
     * @return array
     */
    public function getAllowedRules();

    /**
     * @return array
     */
    public function getDeniedRules();

    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $rule
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function assert(RuleTransfer $rule, $bundle, $controller, $action);

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAccessible($bundle, $controller, $action);
}
