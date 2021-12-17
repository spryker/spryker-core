<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend\Controller;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\Kernel\Backend\Exception\InvalidAbstractFactoryException;
use Spryker\Glue\Kernel\Controller\AbstractController as GlueAbstractController;

abstract class AbstractController extends GlueAbstractController
{
    /**
     * @throws \Spryker\Glue\Kernel\Backend\Exception\InvalidAbstractFactoryException
     *
     * @return \Spryker\Glue\Kernel\Backend\AbstractFactory
     */
    protected function getFactory(): AbstractFactory
    {
        parent::getFactory();

        if (!$this->factory instanceof AbstractFactory) {
            throw new InvalidAbstractFactoryException(sprintf(
                'Modules implementing a %s need to extend the %s',
                static::class,
                AbstractFactory::class,
            ));
        }

        return $this->factory;
    }
}
