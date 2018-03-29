<?php

namespace Spryker\Zed\Customer\Communication\Plugin;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 */
class OfferCustomerHydratorPlugin extends AbstractPlugin implements OfferHydratorPluginInterface
{
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        $customerTransfer = $this->getFacade()->findByReference($offerTransfer->getCustomerReference());
        $offerTransfer->setCustomer($customerTransfer);

        return $offerTransfer;
    }
}