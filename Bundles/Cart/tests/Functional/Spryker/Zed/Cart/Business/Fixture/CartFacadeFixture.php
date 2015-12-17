<?php

namespace Functional\Spryker\Zed\Cart\Fixture;

use Spryker\Shared\Config;
use Spryker\Shared\Kernel\Factory\FactoryInterface;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\Cart\CartConfig;

class CartFacadeFixture extends CartFacade
{

    private $mockBusinessFactory;

    public function __construct(FactoryInterface $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->mockBusinessFactory = new CartFixtureBusinessFactory(
            $factory,
            $locator,
            new CartConfig(Config::getInstance(), $locator)
        );
    }

    protected function getFactory()
    {
        return $this->mockBusinessFactory;
    }

}
