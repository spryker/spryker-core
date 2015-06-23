<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart\CartStorage;

/**
 * Interface CartStorageInterface
 * @package SprykerFeature\Yves\Cart\CartStorage
 */
interface CartStorageInterface
{
    /**
     * @return string
     */
    public function getCartHash();

    /**
     * @param string $cartHash
     */
    public function setCartHash($cartHash);

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function loadCartFromHash();
}
