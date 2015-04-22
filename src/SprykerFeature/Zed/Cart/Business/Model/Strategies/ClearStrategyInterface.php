<?php

namespace SprykerFeature\Zed\Cart\Business\Model\Strategies;

interface ClearStrategyInterface
{

    /**
     * @param  \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cartStorage
     * @param  null                                  $deleteCause
     * @return int
     */
    public function clearCartStorage(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cartStorage, $deleteCause = null);

    /**
     * @param  \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $cartItem
     * @param  null                                      $deleteCause
     * @return void
     */
    public function clearCartItem(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $cartItem, $deleteCause = null);

    /**
     * @param \PropelObjectCollection $cartItemCollection
     * @param int                    $deleteCause
     */
    public function clearCartItems(\PropelObjectCollection $cartItemCollection, $deleteCause = null);

}
