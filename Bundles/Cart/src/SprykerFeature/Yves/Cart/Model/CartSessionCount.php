<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart\Model;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SessionCartCount
 * @package SprykerFeature\Yves\Cart\Model
 */
class CartSessionCount implements CartCountInterface
{
    const SESSION_CART_COUNT_KEY = 'CART_COUNT';

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var int
     */
    protected static $count = null;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

    }

    public function __destruct()
    {
        $this->loadCount();
        $this->session->set(self::SESSION_CART_COUNT_KEY, self::$count);
    }

    protected function loadCount()
    {
        if (self::$count === null) {
            self::$count = $this->session->get(self::SESSION_CART_COUNT_KEY, 0);
        }
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        self::$count = $count;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $this->loadCount();
        return self::$count;
    }
}
