<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\ClassResolver\ClassInfo;
use SprykerFeature\Shared\System\SystemConfig;

class BundleConfigNotFoundException extends \Exception
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
            'Can not resolve "%s" Config in for your bundle "%s"',
            $callerClassInfo->getApplication(),
            $callerClassInfo->getLayer(),
            $callerClassInfo->getBundle()
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing Config class to your bundle.';

        $message .= sprintf(
            'E.g. %s\\%s\\%s\\%s\\%sDependencyContainer',
            Config::getInstance()->get(SystemConfig::PROJECT_NAMESPACE),
            $callerClassInfo->getApplication(),
            $callerClassInfo->getBundle(),
            $callerClassInfo->getLayer(),
            $callerClassInfo->getBundle()
        );

        return $message;
    }


}
