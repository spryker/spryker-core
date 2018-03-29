<?php


namespace Spryker\Zed\Offer\Business\Model;


use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface;

class OfferPluginExecutor implements OfferPluginExecutorInterface
{
    /**
     * @var OfferHydratorPluginInterface[]
     */
    protected $hydratorPlugins;

    /**
     * @param OfferHydratorPluginInterface[] $hydratorPlugins
     */
    public function __construct(array $hydratorPlugins) {
        $this->hydratorPlugins = $hydratorPlugins;
    }

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        foreach ($this->hydratorPlugins as $offerHydratorPlugin) {
            $offerTransfer = $offerHydratorPlugin->hydrateOffer($offerTransfer);
        }

        return $offerTransfer;
    }

}