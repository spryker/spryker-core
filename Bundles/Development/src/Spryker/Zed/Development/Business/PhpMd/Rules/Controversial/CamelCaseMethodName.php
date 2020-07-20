<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\PhpMd\Rules\Controversial;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\MethodAware;

class CamelCaseMethodName extends AbstractRule implements MethodAware
{
    /**
     * @var array
     */
    protected $ignoredMethods = [
        '__construct',
        '__destruct',
        '__set',
        '__get',
        '__call',
        '__callStatic',
        '__isset',
        '__unset',
        '__sleep',
        '__wakeup',
        '__toString',
        '__invoke',
        '__set_state',
        '__clone',
        '__debugInfo',
        // codeception method names
        '_setConfig',
        '_getConfig',
        '_resetConfig',
        '_getName',
        '_hasRequiredFields',
        '_reconfigure',
        '_beforeSuite',
        '_afterSuite',
        '_beforeStep',
        '_afterStep',
        '_before',
        '_before',
        '_depends',
        '_inject',
    ];

    /**
     * This method checks if a method is not named in camelCase
     * and emits a rule violation.
     *
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        $methodName = $node->getName();
        if (!in_array($methodName, $this->ignoredMethods)) {
            if (!$this->isValid($methodName)) {
                $this->addViolation(
                    $node,
                    [
                        $methodName,
                    ]
                );
            }
        }
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    private function isValid($methodName)
    {
        if ($this->getBooleanProperty('allow-underscore-test') && strpos($methodName, 'test') === 0) {
            return (bool)preg_match('/^test[a-zA-Z0-9]*([_][a-z][a-zA-Z0-9]*)?$/', $methodName);
        }

        if ($this->getBooleanProperty('allow-underscore')) {
            return (bool)preg_match('/^[_]?[a-z][a-zA-Z0-9]*$/', $methodName);
        }

        return (bool)preg_match('/^[a-z][a-zA-Z0-9]*$/', $methodName);
    }
}
