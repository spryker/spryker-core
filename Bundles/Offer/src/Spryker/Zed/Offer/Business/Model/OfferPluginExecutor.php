<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferTransfer;

class OfferPluginExecutor implements OfferPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface[]
     */
    protected $hydratorPlugins;

    /**
     * @param \Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface[] $hydratorPlugins
     */
    public function __construct(array $hydratorPlugins)
    {
        $this->hydratorPlugins = $hydratorPlugins;
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
}
