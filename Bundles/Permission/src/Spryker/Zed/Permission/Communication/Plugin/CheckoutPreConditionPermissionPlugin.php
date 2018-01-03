<?php

namespace Spryker\Zed\Permission\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Permission\Plugin\OrderCreatePermissionPlugin;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @example
 */
class CheckoutPreConditionPermissionPlugin extends AbstractPlugin implements \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface
{
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $can = $this->getFactory()
            ->getZedPermission()
            ->can(OrderCreatePermissionPlugin::PERMISSION_KEY, [
                'amount' => $quoteTransfer->getTotals()->getGrandTotal()
            ]);

        if ($can) {
            return true;
        }

        $checkoutResponseTransfer->addError(
                (new CheckoutErrorTransfer())
                    ->setMessage('123')
            );

        return false;
    }

}