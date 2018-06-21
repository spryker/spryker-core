<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel;

use Spryker\Glue\Kernel\ClassResolver\RestResource\ResourceResolver;
use Spryker\Shared\Kernel\AbstractLocator;

class ResourceLocator extends AbstractLocator
{
    const SERVICE_SUFFIX = 'Resource';

    /**
     * @var string
     */
    protected $application = 'Glue';

    /**
     * @api
     *
     * @param string $bundle
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function locate($bundle)
    {
        $restResourceResolver = new ResourceResolver();
        return $restResourceResolver->resolve($bundle);
    }
}
