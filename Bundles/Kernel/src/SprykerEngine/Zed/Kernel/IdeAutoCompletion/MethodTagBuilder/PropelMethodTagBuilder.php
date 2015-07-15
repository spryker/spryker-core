<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
