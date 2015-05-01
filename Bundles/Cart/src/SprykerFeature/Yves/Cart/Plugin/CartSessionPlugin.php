<?php

namespace SprykerFeature\Yves\Cart\Plugin;

use SprykerFeature\Yves\Library\Session\TransferSession;
use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Cart\Model\CartSession;

/**
 * Class CartSessionPlugin
 * @package SprykerFeature\Yves\Cart
 */
class CartSessionPlugin extends AbstractPlugin
{
    /**
     * @param TransferSession $transferSession
     * @return CartSession
     */
    public function createCartSession(TransferSession $transferSession)
    {
        return $this->getDependencyContainer()->createCartSession($transferSession);
    }
}
