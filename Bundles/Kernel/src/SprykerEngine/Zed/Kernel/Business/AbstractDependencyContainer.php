<?php

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use SprykerEngine\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractDependencyContainer extends BaseDependencyContainer implements DependencyContainerInterface
{

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;
    
    /**
     * @return AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @param AbstractQueryContainer $queryContainer
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }
}
