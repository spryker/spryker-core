<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Cart\CartDependencyContainer;
use SprykerFeature\Yves\Cart\Provider\CartServiceProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartServicePlugin extends AbstractPlugin
{
    /**
     * @param SessionInterface $session
     *
     * @return CartServiceProvider
     */
    public function createCartServiceProvider(SessionInterface $session)
    {
        return $this->getDependencyContainer()->createCartClient($session);
    }
}
