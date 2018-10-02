<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;

class QueryContainerLocator extends AbstractLocator
{
    public const PROPEL_CONNECTION = 'propel connection';

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
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    public function locate($bundle)
    {
        return $this->getQueryContainerResolver()->resolve($bundle);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver
     */
    protected function getQueryContainerResolver()
    {
        return new QueryContainerResolver();
    }
}
