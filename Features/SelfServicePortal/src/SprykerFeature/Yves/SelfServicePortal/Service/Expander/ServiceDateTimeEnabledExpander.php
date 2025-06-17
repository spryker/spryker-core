<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

class ServiceDateTimeEnabledExpander implements ServiceDateTimeEnabledExpanderInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var string
     */
    protected const IS_SERVICE_DATE_TIME_ENABLED = 'is_service_date_time_enabled';

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithServiceDateTimeEnabled(ItemTransfer $itemTransfer, string $locale): ItemTransfer
    {
        /** @var string $sku */
        $sku = $itemTransfer->getSku();

        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageDataByMapping(
                static::PRODUCT_CONCRETE_MAPPING_TYPE,
                $sku,
                $locale,
            );

        if (!$productConcreteStorageData) {
            return $itemTransfer;
        }

        $isServiceDateTimeEnabled = isset($productConcreteStorageData[static::IS_SERVICE_DATE_TIME_ENABLED])
            ? (bool)$productConcreteStorageData[static::IS_SERVICE_DATE_TIME_ENABLED]
            : false;

        return $itemTransfer->setIsServiceDateTimeEnabled($isServiceDateTimeEnabled);
    }
}
