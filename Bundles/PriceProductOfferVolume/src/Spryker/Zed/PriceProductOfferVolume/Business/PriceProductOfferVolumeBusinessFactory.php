<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business;

use Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductOfferVolume\PriceProductOfferVolumeDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductOfferVolume\PriceProductOfferVolumeConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferVolume\Persistence\PriceProductOfferVolumeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductOfferVolume\Persistence\PriceProductOfferVolumeRepositoryInterface getRepository()
 */
class PriceProductOfferVolumeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface
     */
    public function getPriceProductOfferVolumeService(): PriceProductOfferVolumeServiceInterface
    {
        return $this->getProvidedDependency(PriceProductOfferVolumeDependencyProvider::SERVICE_PRICE_PRODUCT_OFFER_VOLUME);
    }
}
