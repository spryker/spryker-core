<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\PhpMd\Rules\Naming;

use OutOfBoundsException;
use PHPMD\AbstractNode;
use PHPMD\Rule\Naming\ShortVariable as PHPMDShortVariable;

class ShortVariable extends PHPMDShortVariable
{
    /**
     * Template method that performs the real node image check.
     *
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    protected function checkMinimumLength(AbstractNode $node)
    {
        if ($this->hasMinimumLength($node)) {
            return;
        }
        if ($this->isNameAllowedInContext($node)) {
            return;
        }
        if ($this->isInExceptionList($node)) {
            return;
        }
        if ($this->isCestFile($node)) {
            return;
        }
        $threshold = $this->getIntProperty('minimum');
        $this->addViolation($node, [$node->getImage(), $threshold]);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    private function hasMinimumLength(AbstractNode $node)
    {
        $threshold = $this->getIntProperty('minimum');
        if ($threshold <= strlen($node->getImage()) - 1) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    private function isInExceptionList(AbstractNode $node)
    {
        $exceptions = $this->getExceptionsList();

        if (in_array(substr($node->getImage(), 1), $exceptions)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    private function isCestFile(AbstractNode $node)
    {
        $fileName = $node->getFileName();
        if (strpos($fileName, 'Cest.php') !== false) {
            return true;
        }

        return false;
    }

    /**
     * Gets array of exceptions from property
     *
     * @return string[]
     */
    private function getExceptionsList()
    {
        try {
            $exceptions = $this->getStringProperty('exceptions');
        } catch (OutOfBoundsException $e) {
            $exceptions = '';
        }

        return explode(',', $exceptions);
    }

    /**
     * Checks if a short name is acceptable in the current context. For the
     * moment these contexts are the init section of a for-loop and short
     * variable names in catch-statements.
     *
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    private function isNameAllowedInContext(AbstractNode $node)
    {
        return $this->isChildOf($node, 'CatchStatement')
                || $this->isChildOf($node, 'ForInit')
                || $this->isChildOf($node, 'ForeachStatement')
                || $this->isChildOf($node, 'MemberPrimaryPrefix');
    }

    /**
     * Checks if the given node is a direct or indirect child of a node with
     * the given type.
     *
     * @param \PHPMD\AbstractNode $node
     * @param string $type
     *
     * @return bool
     */
    private function isChildOf(AbstractNode $node, $type)
    {
        $parent = $node->getParent();
        while (is_object($parent)) {
            if ($parent->isInstanceOf($type)) {
                return true;
            }
            $parent = $parent->getParent();
        }

        return false;
    }
}
