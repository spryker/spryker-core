<?php

namespace SprykerFeature\Yves\Cart;

use Generated\Yves\Ide\FactoryAutoCompletion\Cart;
use SprykerFeature\Yves\Library\Session\TransferSession;
use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Yves\Cart\CartStorage\ZedStorage;
use SprykerFeature\Yves\Cart\Model\CartSessionCount;
use SprykerFeature\Yves\Cart\Helper\ItemGrouper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CartDependencyContainer
 * @package SprykerFeature\Yves\Cart
 */
class CartDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var Cart
     */
    protected $factory;

    /**
     * @param Application $app
     * @param bool $sslEnabled
     * @return Router\CartRouter
     */
    public function createCartRouter(Application $app, $sslEnabled = false)
    {
        $cartSession = $this->createCartSession($app->getTransferSession());

        return $this->getFactory()->createRouterCartRouter(
            $cartSession,
            $app,
            $sslEnabled
        );
    }

    /**
     * @param TransferSession $transferSession
     * @param Request $request
     * @param \ArrayObject $cookieBag
     * @param SessionInterface $session
     * @return Model\ZedCart
     */
    public function createZedCart(
        TransferSession $transferSession,
        Request $request,
        \ArrayObject $cookieBag,
        SessionInterface $session
    ) {
        return $this->getFactory()->createModelZedCart(
            $this->createCartSession($transferSession),
            $this->createItemGrouperHelper(),
            $this->getLocator(),
            $this->createZedStorage($request, $cookieBag, $session),
            $this->createCartSessionCount($session)
        );
    }

    /**
     * @param TransferSession $transferSession
     * @return Model\CartSession
     */
    public function createCartSession(TransferSession $transferSession)
    {
        $cartSession = $this->getFactory()->createModelCartSession(
            $transferSession,
            $this->getLocator()
        );

        return $cartSession;
    }

    /**
     * @param SessionInterface $session
     * @return CartSessionCount
     */
    public function createCartSessionCount(SessionInterface $session)
    {
        return $this->getFactory()->createModelCartSessionCount($session);
    }

    /**
     * @return ItemGrouper
     */
    protected function createItemGrouperHelper()
    {
        return $this->getFactory()->createHelperItemGrouper($this->getLocator());
    }

    /**
     * @param Request $request
     * @param \ArrayObject $cookieBag
     * @param SessionInterface $session
     * @return ZedStorage
     */
    protected function createZedStorage(Request $request, \ArrayObject $cookieBag, SessionInterface $session)
    {
        return $this->getFactory()->createCartStorageZedStorage(
            $request,
            $cookieBag,
            $session
        );
    }
}
