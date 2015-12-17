<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Persistence\Fixtures;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

class KernelQueryContainer extends AbstractQueryContainer
{

    public function getDepCon()
    {
        return $this->getFactory();
    }

}
