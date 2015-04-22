<?php

namespace SprykerFeature\Zed\Cart\Business\Model;

use SprykerFeature\Shared\Cart\Transfer\StepStorage;

class CartStep
{

    const CART_STARTING_POSITION = 1;

    /**
     * @param  StepStorage $transfer
     * @return StepStorage
     */
    public function storeCartStep(StepStorage $transfer)
    {
        $cartUser = $this->getCartUser($transfer->getUserId());
        if ($cartUser) {
            $cartUserStep = $cartUser->getCartUserStep();
            if (!$cartUserStep) {
                $this->createCartUserStep($cartUser, $transfer->getStepName());
            } else {
                $cartUserStep->setStep($transfer->getStepName());
                $cartUserStep->setCurrentPosition(self::CART_STARTING_POSITION);

                //important to manually set updatedAt because if already at same step and position 1 orm will
                //think it is not modified
                $cartUserStep->setUpdatedAt(new DateTime());
                $cartUserStep->save();
            }
            $transfer->setIsSuccess(true);

            return $transfer;
        } else {
            $transfer->setIsSuccess(false);
        }

        return $transfer;
    }

    /**
     * @param \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartUser $cartUser
     * @param $stepName
     * @param  int                                           $currentPosition
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartUserStep
     */
    protected function createCartUserStep(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartUser $cartUser, $stepName, $currentPosition = 1)
    {
        $entity = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartUserStep();
        $entity->setCartUser($cartUser);
        $entity->setStep($stepName);
        $entity->setCurrentPosition($currentPosition);
        $entity->save();

        return $entity;
    }

    /**
     * @param $customerId
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartUser|null
     */
    protected function getCartUser($customerId)
    {
        return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartUserQuery::create()->findOneByFkCustomer($customerId);
    }
}
