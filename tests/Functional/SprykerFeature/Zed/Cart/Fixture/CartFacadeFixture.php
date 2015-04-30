<?php

namespace Functional\SprykerFeature\Zed\Cart\Fixture;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Cart\Business\CartFacade;

class CartFacadeFixture extends CartFacade
{
    private $mockDependencyContainer;

    public function __construct(FactoryInterface $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->mockDependencyContainer = new CartFixtureDependencyContainer($factory, $locator);
    }


    protected function getDependencyContainer()
    {
        return $this->mockDependencyContainer;
    }
}
 