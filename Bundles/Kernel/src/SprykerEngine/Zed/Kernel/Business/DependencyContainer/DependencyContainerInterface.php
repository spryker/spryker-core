<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Business\DependencyContainer;

use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

interface DependencyContainerInterface
{

    /**
     * @param Container $container
     */
    public function setContainer(Container $container);

    /**
     * @param AbstractQueryContainer $queryContainer
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer);

}
