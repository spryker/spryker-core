<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel\ClassResolver\Client;

use Spryker\Shared\Config;
use Spryker\Client\Kernel\ClassResolver\ClassInfo;
use Spryker\Shared\Application\ApplicationConfig;

class ClientNotFoundException extends \Exception
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
            'Can not resolve %1$sClient for your bundle "%1$s"',
            $callerClassInfo->getBundle()
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing Client to your bundle.' . PHP_EOL;

        $message .= sprintf(
            'E.g. %s\\Client\\%2$s\\Service\\%2$sClient',
            Config::getInstance()->get(ApplicationConfig::PROJECT_NAMESPACE),
            $callerClassInfo->getBundle()
        );

        return $message;
    }

}
