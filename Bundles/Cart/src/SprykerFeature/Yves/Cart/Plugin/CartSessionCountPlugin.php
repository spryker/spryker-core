<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Cart\CartDependencyContainer;
use SprykerFeature\Yves\Cart\Model\CartSessionCount;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartSessionCountPlugin extends AbstractPlugin
{
    /**
     * @param SessionInterface $session
     * @return CartSessionCount
     */
    public function createCartSessionCount(SessionInterface $session)
    {
        return $this->getDependencyContainer()->createCartSessionCount($session);
    }
}
