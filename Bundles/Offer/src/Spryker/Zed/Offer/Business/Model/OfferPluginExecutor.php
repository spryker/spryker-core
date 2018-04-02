<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;


use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Offer\Dependency\Plugin\OfferDoUpdatePluginInterface;
use Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface;

class OfferPluginExecutor implements OfferPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface[]
     */
    protected $hydratorPlugins;

    /**
     * @var OfferDoUpdatePluginInterface[]
     */
    protected $doUpdatePlugins;

    /**
     * @param OfferHydratorPluginInterface[] $hydratorPlugins
     * @param OfferDoUpdatePluginInterface[] $doUpdatePlugins
     */
    public function __construct(
        array $hydratorPlugins,
        array $doUpdatePlugins
    ) {
        $this->hydratorPlugins = $hydratorPlugins;
        $this->doUpdatePlugins = $doUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        foreach ($this->hydratorPlugins as $offerHydratorPlugin) {
            $offerTransfer = $offerHydratorPlugin->hydrateOffer($offerTransfer);
        }

        return $offerTransfer;
    }

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerResponseTransfer = new OfferResponseTransfer();
        $offerResponseTransfer->setOffer($offerTransfer);

        foreach ($this->doUpdatePlugins as $doUpdatePlugin) {
            $offerResponseTransfer = $this->mergeOfferResponses(
                $offerResponseTransfer,
                $doUpdatePlugin->updateOffer($offerTransfer)
            );
        }

        return $offerResponseTransfer;
    }

    /**
     * @param OfferResponseTransfer $offerResponseTransfer
     * @param OfferResponseTransfer $pluginOfferResponseTransfer
     *
     * @return OfferResponseTransfer
     */
    protected function mergeOfferResponses(OfferResponseTransfer $offerResponseTransfer, OfferResponseTransfer $pluginOfferResponseTransfer)
    {
        if ($offerResponseTransfer->getIsSuccessful()) {
            $offerResponseTransfer->setIsSuccessful($pluginOfferResponseTransfer->getIsSuccessful());
        }

        foreach ($pluginOfferResponseTransfer->getMessages() as $responseMessageTransfer) {
            $offerResponseTransfer->addMessage($responseMessageTransfer);
        }

        return $offerResponseTransfer;
    }
}
