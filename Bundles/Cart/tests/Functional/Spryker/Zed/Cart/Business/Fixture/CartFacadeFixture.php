<?php

namespace Functional\Spryker\Zed\Cart\Fixture;

use Spryker\Shared\Config;
use Spryker\Shared\Kernel\Factory\FactoryInterface;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\Cart\CartConfig;

class CartFacadeFixture extends CartFacade
{

    private $mockDependencyContainer;

    public function __construct(FactoryInterface $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->mockDependencyContainer = new CartFixtureDependencyContainer(
            $factory,
            $locator,
            new CartConfig(Config::getInstance(), $locator)
        );
    }

    protected function getDependencyContainer()
    {
        return $this->mockDependencyContainer;
    }

}
