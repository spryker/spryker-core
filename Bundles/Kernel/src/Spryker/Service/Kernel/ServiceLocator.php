<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Service\Kernel\ClassResolver\Service\ServiceResolver;
use Spryker\Shared\Kernel\AbstractLocator;

class ServiceLocator extends AbstractLocator
{
    public const SERVICE_SUFFIX = 'Service';

    /**
     * @var string
     */
    protected $application = 'Service';

    /**
     * @api
     *
     * @param string $bundle
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function locate($bundle)
    {
        $serviceResolver = new ServiceResolver();
        $service = $serviceResolver->resolve($bundle);

        return $service;
    }
}
