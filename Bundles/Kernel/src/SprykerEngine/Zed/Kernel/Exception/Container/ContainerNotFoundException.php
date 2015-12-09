<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Exception\Container;

use SprykerEngine\Zed\Kernel\ClassResolver\ClassInfo;

class ContainerNotFoundException extends \Exception
{

    /**
     * @param object $callerClass
     */
    public function __construct($callerClass)
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($callerClass);

        parent::__construct($this->buildMessage($classInfo));
    }

    /**
     * @param ClassInfo $callerClassInfo
     *
     * @return string
     */
    private function buildMessage(ClassInfo $callerClassInfo)
    {
        $message = 'Spryker Kernel Exception' . PHP_EOL;
        $message .= sprintf(
                'Container not set in "%s"',
                $callerClassInfo->getCallerClassName()
            ) . PHP_EOL;

        $message .= 'This usually happens in tests when you use a DependencyContainer without setting a container.';

        return $message;
    }

}
