<?php


namespace Spryker\Zed\OfferGui\Dependency\Facade;


use Generated\Shared\Transfer\OfferResponseTransfer;
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

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     *
     * @throws \Exception
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        return $this->offerFacade->updateOffer($offerTransfer);
    }
}