<?php

namespace SprykerFeature\Zed\Cart\Business\Model\Strategies;

class ClearStrategyDelete implements ClearStrategyInterface
{

    /**
     * @param  \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cartStorage
     * @param  int                                   $deleteCause
     * @return int
     */
    public function clearCartStorage(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cartStorage, $deleteCause = null)
    {
        if ($cartStorage) {
            $cartItemQuery = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItemQuery();
            $deletedAmount = $cartItemQuery->filterByCart($cartStorage)
                ->delete();

            return $deletedAmount;
        }

        return 0;
    }

    /**
     * @param  \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $cartItem
     * @param  null                                      $deleteCause
     * @return void
     */
    public function clearCartItem(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $cartItem, $deleteCause = null)
    {
        $cartItem->delete();
    }

    /**
     * @param PropelObjectCollection $cartItemCollection
     * @param int                    $deleteCause
     */
    public function clearCartItems(PropelObjectCollection $cartItemCollection, $deleteCause = null)
    {
        $cartItemCollection->delete();
    }

}
