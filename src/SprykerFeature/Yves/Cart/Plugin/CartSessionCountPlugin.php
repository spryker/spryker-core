<?php

namespace SprykerFeature\Yves\Cart\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Cart\Model\CartSessionCount;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CartSessionPlugin
 * @package SprykerFeature\Yves\Cart
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
