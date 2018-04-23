<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;

class OfferPluginExecutor implements OfferPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface[]
     */
    protected $hydratorPlugins;

    /**
     * @var \Spryker\Zed\Offer\Dependency\Plugin\OfferDoUpdatePluginInterface[]
     */
    protected $doUpdatePlugins;

    /**
     * @param \Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface[] $hydratorPlugins
     * @param \Spryker\Zed\Offer\Dependency\Plugin\OfferDoUpdatePluginInterface[] $doUpdatePlugins
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
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        $offerResponseTransfer = new OfferResponseTransfer();
        $offerResponseTransfer->setOffer($offerTransfer);
        $offerResponseTransfer->setIsSuccessful(true);

        foreach ($this->doUpdatePlugins as $doUpdatePlugin) {
            $offerResponseTransfer = $this->mergeOfferResponses(
                $offerResponseTransfer,
                $doUpdatePlugin->updateOffer($offerTransfer)
            );
        }

        return $offerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferResponseTransfer $offerResponseTransfer
     * @param \Generated\Shared\Transfer\OfferResponseTransfer $pluginOfferResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
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
