<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Persistence\Fixtures;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

class KernelQueryContainer extends AbstractQueryContainer
{

    public function getDepCon()
    {
        return $this->getDependencyContainer();
    }

}
