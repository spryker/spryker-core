<?php

namespace Spryker\Zed\Customer\Communication\Plugin\Offer;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 */
class OfferCustomerHydratorPlugin extends AbstractPlugin implements OfferHydratorPluginInterface
{
    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        $customerTransfer = $this->getFacade()->findByReference($offerTransfer->getCustomerReference());
        $offerTransfer->setCustomer($customerTransfer);

        return $offerTransfer;
    }
}