<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

class PropelMethodTagBuilder extends ConstructableMethodTagBuilder
{

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function ignoreClass($className)
    {
        if (!class_exists($className) || preg_match('/(\\\\Map\\\\|\\\\Base\\\\)/', $className)) {
            return true;
        }

        return false;
    }

}
