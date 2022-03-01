<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Controller;

use Spryker\Glue\Kernel\AbstractStorefrontApiFactory;
use Spryker\Glue\Kernel\Exception\Factory\InvalidAbstractFactoryException;

class AbstractStorefrontApiController extends AbstractController
{
    /**
     * @throws \Spryker\Glue\Kernel\Exception\Factory\InvalidAbstractFactoryException
     *
     * @return \Spryker\Glue\Kernel\AbstractStorefrontApiFactory
     */
    protected function getFactory(): AbstractStorefrontApiFactory
    {
        parent::getFactory();

        if (!$this->factory instanceof AbstractStorefrontApiFactory) {
            throw new InvalidAbstractFactoryException(sprintf(
                'Modules implementing a %s need to extend the %s',
                static::class,
                AbstractStorefrontApiFactory::class,
            ));
        }

        return $this->factory;
    }
}
