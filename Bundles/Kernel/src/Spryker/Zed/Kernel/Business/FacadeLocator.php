<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;

class FacadeLocator extends AbstractLocator
{
    public const FACADE_SUFFIX = 'Facade';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $layer = 'Business';

    /**
     * @var string
     */
    protected $application = 'Zed';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @api
     *
     * @param string $bundle
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function locate($bundle)
    {
        $facadeResolver = new FacadeResolver();
        $facade = $facadeResolver->resolve($bundle);

        return $facade;
    }
}
