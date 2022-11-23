<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfiguration\Business\ProductConfigurationBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationEntityManagerInterface getEntityManager()
 */
class ProductConfigurationFacade extends AbstractFacade implements ProductConfigurationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
    ): ProductConfigurationCollectionTransfer {
        return $this->getFactory()
            ->createProductConfigurationReader()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);
    }
}
