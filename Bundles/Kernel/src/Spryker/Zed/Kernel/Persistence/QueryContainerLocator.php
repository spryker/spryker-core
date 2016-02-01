<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Shared\Kernel\AbstractLocator;

class QueryContainerLocator extends AbstractLocator
{

    const PROPEL_CONNECTION = 'propel connection';

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
     * @param string $bundle
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException
     *
     * @return AbstractQueryContainer
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
