<?php

namespace SprykerFeature\Zed\Cart\Business\Model\Strategies;

use SprykerFeature\Shared\Cart\Code\DeleteReasonConstant;

class ClearStrategyMarkDelete implements ClearStrategyInterface
{

    /**
     * @param  \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cartStorage
     * @param  int                                   $deleteCause
     * @return int
     */
    public function clearCartStorage(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cartStorage, $deleteCause = null)
    {
        if ($cartStorage) {
            $deleteCause = is_int($deleteCause) ? $deleteCause : DeleteReasonConstant::DELETE_REASON_CAUSE_UNDEFINED;
            $updateData = array('IsDeleted' => true, 'DeleteCause' => $deleteCause);
            $cartItemQuery = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItemQuery();
            $updatedAmount = $cartItemQuery->filterByCart($cartStorage)
                ->filterByIsDeleted(false)
                ->update($updateData);

            return $updatedAmount;
        }

        return 0;
    }

    /**
     * @param  \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $cartItem
     * @param  null                                      $deleteCause
     * @return mixed
     */
    public function clearCartItem(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $cartItem, $deleteCause = null)
    {
        $deleteCause = is_int($deleteCause) ? $deleteCause : DeleteReasonConstant::DELETE_REASON_CAUSE_UNDEFINED;
        // TODO isDeleted no longer supported
        // $cartItem->setIsDeleted(true);
        $cartItem->setDeleteCause($deleteCause);
        $cartItem->save();
    }

    /**
     * @param PropelObjectCollection $cartItemCollection
     * @param int                    $deleteCause
     */
    public function clearCartItems(PropelObjectCollection $cartItemCollection, $deleteCause = null)
    {
        foreach ($cartItemCollection as $cartItem) {
            $this->clearCartItem($cartItem, $deleteCause);
        }
    }
}
