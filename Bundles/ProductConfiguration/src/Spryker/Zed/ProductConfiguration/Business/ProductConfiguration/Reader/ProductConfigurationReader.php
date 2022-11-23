<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\ProductConfiguration\Reader;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;
use Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepositoryInterface;

class ProductConfigurationReader implements ProductConfigurationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepositoryInterface
     */
    protected ProductConfigurationRepositoryInterface $productConfigurationRepository;

    /**
     * @param \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepositoryInterface $productConfigurationRepository
     */
    public function __construct(
        ProductConfigurationRepositoryInterface $productConfigurationRepository
    ) {
        $this->productConfigurationRepository = $productConfigurationRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
    ): ProductConfigurationCollectionTransfer {
        return $this->productConfigurationRepository
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);
    }
}
