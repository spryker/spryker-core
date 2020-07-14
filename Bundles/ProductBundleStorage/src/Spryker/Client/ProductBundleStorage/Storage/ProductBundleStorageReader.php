<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Storage;

use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductBundleStorage\Mapper\ProductBundleStorageMapperInterface;

class ProductBundleStorageReader implements ProductBundleStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductBundleStorage\Mapper\ProductBundleStorageMapperInterface
     */
    protected $productBundleStorageMapper;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductBundleStorage\Mapper\ProductBundleStorageMapperInterface $productBundleStorageMapper
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductBundleStorageMapperInterface $productBundleStorageMapper,
        ProductBundleStorageToSynchronizationServiceInterface $synchronizationService,
        ProductBundleStorageToStorageClientInterface $storageClient,
        ProductBundleStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->productBundleStorageMapper = $productBundleStorageMapper;
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
        $this->utilEncodingService = $utilEncodingService;
    }
}
