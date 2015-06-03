<?php

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
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
    public function setQueryContainer($queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }
}
