<?php

namespace SprykerEngine\Zed\Kernel\Business\DependencyContainer;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

interface DependencyContainerInterface
{

    /**
     * @param AbstractQueryContainer $queryContainer
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer);
}
