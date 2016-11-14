<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Service\Kernel\ClassResolver\Service\ServiceResolver;

class ServiceLocator extends AbstractLocator
{

    const SERVICE_SUFFIX = 'Service';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $application = 'Service';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @api
     *
     * @param string $bundle
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function locate($bundle)
    {
        $facadeResolver = new ServiceResolver();
        $facade = $facadeResolver->resolve($bundle);

        return $facade;
    }

}
