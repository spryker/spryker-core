<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\ClassResolver\DependencyContainer;

use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Exception\Backtrace;
use Symfony\Component\Routing\Exception\ExceptionInterface;

class ControllerNotFoundException extends \Exception implements ExceptionInterface
{

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
     */
    public function __construct(BundleControllerActionInterface $bundleControllerAction)
    {
        parent::__construct($this->buildMessage($bundleControllerAction));
    }

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function buildMessage(BundleControllerActionInterface $bundleControllerAction)
    {
        $message = 'Spryker Kernel Exception' . PHP_EOL;
        $message .= sprintf(
            'Can not resolve %2$sController for your bundle "%1$s"',
            $bundleControllerAction->getBundle(),
            $bundleControllerAction->getController()
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing Controller to your bundle.' . PHP_EOL;

        $message .= new Backtrace();

        return $message;
    }

}
