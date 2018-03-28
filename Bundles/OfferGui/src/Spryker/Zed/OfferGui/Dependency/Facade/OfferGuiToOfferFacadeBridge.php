<?php


namespace Spryker\Zed\OfferGui\Dependency\Facade;


use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Offer\Business\OfferFacadeInterface;

class OfferGuiToOfferFacadeBridge implements OfferGuiToOfferFacadeInterface
{
    /**
     * @var OfferFacadeInterface
     */
    protected $offerFacade;

    /**
     * @param OfferFacadeInterface $offerFacade
     */
    public function __construct($offerFacade)
    {
        $this->offerFacade = $offerFacade;
    }

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer
    {
        return $this->offerFacade->getOfferById($offerTransfer);
    }
}