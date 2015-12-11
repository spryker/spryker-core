<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel\ClassResolver\DependencyContainer;

use Spryker\Shared\Config;
use Spryker\Client\Kernel\ClassResolver\ClassInfo;
use Spryker\Shared\Application\ApplicationConfig;

class DependencyContainerNotFoundException extends \Exception
{

    /**
     * @param ClassInfo $callerClassInfo
     */
    public function __construct(ClassInfo $callerClassInfo)
    {
        parent::__construct($this->buildMessage($callerClassInfo));
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
            'Can not resolve %2$sDependencyContainer for your bundle "%s"',
            $callerClassInfo->getLayer(),
            $callerClassInfo->getBundle()
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing DependencyContainer to your bundle.' . PHP_EOL;

        $message .= sprintf(
            'E.g. %s\\Client\\%2$s\\Service\\%2$sDependencyContainer',
            Config::getInstance()->get(ApplicationConfig::PROJECT_NAMESPACE),
            $callerClassInfo->getBundle()
        );

        return $message;
    }

}
