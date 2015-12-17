<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver\DependencyContainer;

use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\Exception\Backtrace;
use Symfony\Component\Routing\Exception\ExceptionInterface;

class ControllerNotFoundException extends \Exception implements ExceptionInterface
{

    /**
     * @param string $bundle
     * @param string $controller
     */
    public function __construct($bundle, $controller)
    {
        parent::__construct($this->buildMessage($bundle, $controller));
    }

    /**
     * @param $bundle
     * @param $controller
     *
     * @throws \Exception
     * @return string
     */
    protected function buildMessage($bundle, $controller)
    {
        $message = 'Spryker Kernel Exception' . PHP_EOL;
        $message .= sprintf(
            'Can not resolve %2$sController for your bundle "%1$s"',
            $bundle,
            $controller
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing Controller to your bundle.' . PHP_EOL;

        $message .= sprintf(
            'E.g. %s\\Zed\\%s\\Communication\\Controller\\%sController',
            Config::getInstance()->get(ApplicationConstants::PROJECT_NAMESPACE),
            $bundle,
            $controller
        );

        $message .= new Backtrace();

        return $message;
    }

}
