<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart\Plugin;

use SprykerFeature\Yves\Cart\CartDependencyContainer;
use SprykerFeature\Yves\Library\Session\TransferSession;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class ZedCartPlugin extends AbstractPlugin
{
    /**
     * @param TransferSession $transferSession
     * @param Request $request
     * @param \ArrayObject $cookieBag
     * @param SessionInterface $session
     * @return object
     */
    public function createZedCart(
        TransferSession $transferSession,
        Request $request,
        \ArrayObject $cookieBag,
        SessionInterface $session
    ) {
        return $this->getDependencyContainer()->createZedCart($transferSession, $request, $cookieBag, $session);
    }
}
