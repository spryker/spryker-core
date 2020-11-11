<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToProductConfigurationServiceInterface;

class ProductConfigurationInstanceMapper implements ProductConfigurationInstanceMapperInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToProductConfigurationServiceInterface $productConfigurationService
     */
    public function __construct(ProductConfigurationStorageToProductConfigurationServiceInterface $productConfigurationService)
    {
        $this->productConfigurationService = $productConfigurationService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapProductConfigurationStorageTransferToProductConfigurationInstanceTransfer(
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        $productConfigurationInstanceTransfer->fromArray($productConfigurationStorageTransfer->toArray(), true);

        $productConfigurationInstanceTransfer->setConfiguration(
            $productConfigurationStorageTransfer->getDefaultConfiguration()
        );
        $productConfigurationInstanceTransfer->setDisplayData($productConfigurationStorageTransfer->getDefaultDisplayData());
        $productConfigurationInstanceTransfer->setProductConfiguratorInstanceHash(
            $this->productConfigurationService->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer)
        );

        return $productConfigurationInstanceTransfer;
    }
}
