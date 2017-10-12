<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Controller;

use Exception;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Exception\Backtrace;
use Symfony\Component\Routing\Exception\ExceptionInterface;

class ControllerNotFoundException extends Exception implements ExceptionInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     */
    public function __construct(BundleControllerActionInterface $bundleControllerAction)
    {
        parent::__construct($this->buildMessage($bundleControllerAction));
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @return string
     */
    protected function buildMessage(BundleControllerActionInterface $bundleControllerAction)
    {
        $message = 'Spryker Kernel Exception' . PHP_EOL;
        $message .= sprintf(
            'Can not resolve %2$sController for your bundle "%1$s"',
            ucfirst($bundleControllerAction->getBundle()),
            ucfirst($bundleControllerAction->getController())
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing Controller to your bundle.' . PHP_EOL;

        $message .= new Backtrace();

        return $message;
    }
}
