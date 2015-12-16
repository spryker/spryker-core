<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver\Facade;

use Spryker\Shared\Config;
use Spryker\Zed\Kernel\ClassResolver\ClassInfo;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\Exception\Backtrace;

class FacadeNotFoundException extends \Exception
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
    protected function buildMessage(ClassInfo $callerClassInfo)
    {
        $message = 'Spryker Kernel Exception' . PHP_EOL;
        $message .= sprintf(
            'Can not resolve $1Facade in Business layer for your bundle "%s"',
            $callerClassInfo->getBundle()
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing Facade to your bundle.' . PHP_EOL;

        $message .= sprintf(
            'E.g. %s\\Zed\\%2$s\\Business\\%2$sFacade',
            Config::getInstance()->get(ApplicationConstants::PROJECT_NAMESPACE),
            $callerClassInfo->getBundle()
        );

        $message .= new Backtrace();

        return $message;
    }

}
