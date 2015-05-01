<?php

namespace SprykerFeature\Yves\Cart\CartStorage;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ZedStorage implements CartStorageInterface
{
    const COOKIE_HASH_KEY = 'cart_hash';
    const COOKIE_LIFETIME = 1036800; // one year

    const SESSION_HASH_KEY = 'CART_HASH';

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var \ArrayObject
     */
    protected $cookieBag;

    /**
     * @param Request $request
     * @param \ArrayObject $cookieBag
     * @param SessionInterface $session
     */
    public function __construct(Request $request, \ArrayObject $cookieBag, SessionInterface $session)
    {
        $this->request = $request;
        $this->cookieBag = $cookieBag;
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getCartHash()
    {
        $sessionHash = $this->getSessionCartHash();
        if (!empty($sessionHash)) {
            return $sessionHash;
        }

        $cookieHash = $this->getCookieCartHash();
        if (!empty($cookieHash)) {
            return $cookieHash;
        }

        return null;
    }

    /**
     * @param string $cartHash
     */
    public function setCartHash($cartHash)
    {
        $this->setCookieCartHash($cartHash);
        $this->setSessionCartHash($cartHash);
    }

    /**
     * @return string
     */
    protected function getSessionCartHash()
    {
        return $this->session->get(self::SESSION_HASH_KEY);
    }

    /**
     * @param string $hash
     * @return $this
     */
    protected function setSessionCartHash($hash)
    {
        $this->session->set(self::SESSION_HASH_KEY, $hash);

        return $this;
    }

    /**
     * @return null|string
     */
    protected function getCookieCartHash()
    {
        if ($this->request->cookies->has(self::COOKIE_HASH_KEY)) {
            $value = $this->request->cookies->get(self::COOKIE_HASH_KEY);
            $this->setCookieCartHash($value);

            return $value;
        }

        return null;
    }

    /**
     * @param string $hash
     */
    protected function setCookieCartHash($hash)
    {
        $cookie = new Cookie(self::COOKIE_HASH_KEY, $hash, time() + self::COOKIE_LIFETIME, '/', null, false, true);
        $this->cookieBag->append($cookie);
    }

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\Order|void
     */
    public function loadCartFromHash()
    {
//        $cartChange = $this->getBasicCartChangeTransfer();
//        $response = Generated_Yves_Zed::getInstance()->cartLoadCartByHash($cartChange, null, null, true); // background request
//        $this->handleCartChangeResponse($response);
//        return $response;
    }
}
