<?php

namespace Functional\SprykerFeature\Zed\GlossaryDistributor\Mock;

use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Distributor\Business\DistributorDependencyContainer;
use SprykerFeature\Zed\Distributor\Business\DistributorFacade;

class MockDistributorFacade extends DistributorFacade
{

    /**
     * @var DistributorDependencyContainer
     */
    private $mockedDependencyContainer;

    /**
     * @param FactoryInterface $factory
     * @param Locator $locator
     */
    public function __construct(FactoryInterface $factory, Locator $locator)
    {
        $this->mockedDependencyContainer = new DistributorDependencyContainer(
            $factory,
            $locator,
            new MockDistributorConfig(Config::getInstance(), $locator)
        );
    }

    /**
     * @return DistributorDependencyContainer
     */
    protected function getDependencyContainer()
    {
        return $this->mockedDependencyContainer;
    }
}
