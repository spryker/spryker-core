<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend\Controller;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController as AbstractBackendController;
use Spryker\Glue\Kernel\Backend\Exception\InvalidAbstractFactoryException;

class AbstractBackendApiController extends AbstractBackendController
{
    /**
     * @throws \Spryker\Glue\Kernel\Backend\Exception\InvalidAbstractFactoryException
     *
     * @return \Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory
     */
    protected function getFactory(): AbstractBackendApiFactory
    {
        parent::getFactory();

        if (!$this->factory instanceof AbstractBackendApiFactory) {
            throw new InvalidAbstractFactoryException(sprintf(
                'Modules implementing a %s need to extend the %s',
                static::class,
                AbstractBackendApiFactory::class,
            ));
        }

        return $this->factory;
    }
}
