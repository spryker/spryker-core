<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Kernel\AbstractLocator;

class ClientLocator extends AbstractLocator
{
    public const LOCATABLE_SUFFIX = 'Client';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @var string
     */
    protected $application = 'Client';

    /**
     * @api
     *
     * @param string $bundle
     *
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    public function locate($bundle)
    {
        return $this->getClientResolver()->resolve($bundle);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\Client\ClientResolver
     */
    private function getClientResolver()
    {
        return new ClientResolver();
    }
}
