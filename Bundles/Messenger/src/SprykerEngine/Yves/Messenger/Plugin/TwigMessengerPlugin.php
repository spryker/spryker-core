<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Messenger\Plugin;

use SprykerEngine\Shared\Messenger\Communication\Plugin\AbstractTwigMessengerPlugin;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;

class TwigMessengerPlugin extends AbstractTwigMessengerPlugin
{

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator)
    {
    }

}
