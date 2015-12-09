<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\ClassResolver\QueryContainer;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\ClassResolver\ClassInfo;
use SprykerFeature\Shared\System\SystemConfig;

class QueryContainerNotFoundException extends \Exception
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
            'Can not resolve QueryContainer in persistence layer for your bundle "%s"',
            $callerClassInfo->getBundle()
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing QueryContainer class to your bundle.' . PHP_EOL;
        $message .= sprintf(
            'E.g. %1$s\\Zed\\%2$s\\Persistence\\%2$sQueryContainer',
            Config::getInstance()->get(SystemConfig::PROJECT_NAMESPACE),
            $callerClassInfo->getBundle()
        );

        return $message;
    }


}
