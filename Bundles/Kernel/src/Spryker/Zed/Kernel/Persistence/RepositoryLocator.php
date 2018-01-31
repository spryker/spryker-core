<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Zed\Kernel\ClassResolver\Repository\RepositoryResolver;

class RepositoryLocator extends AbstractLocator
{
    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $layer = 'Persistence';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @var string
     */
    protected $application = 'Zed';

    /**
     * @api
     *
     * @param string $bundle
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    public function locate($bundle)
    {
        return $this->getRepositoryResolver()->resolve($bundle);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Repository\RepositoryResolver
     */
    protected function getRepositoryResolver()
    {
        return new RepositoryResolver();
    }
}
