<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Reader;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;
use Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToLocaleClientInterface;
use Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToProductStorageClientInterface;
use Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapperInterface;

class MerchantProductStorageReader implements MerchantProductStorageReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var \Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapperInterface
     */
    protected $merchantProductStorageMapper;

    /**
     * @param \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToLocaleClientInterface $localeClient
     * @param \Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapperInterface $merchantProductStorageMapper
     */
    public function __construct(
        MerchantProductStorageToProductStorageClientInterface $productStorageClient,
        MerchantProductStorageToLocaleClientInterface $localeClient,
        MerchantProductStorageMapperInterface $merchantProductStorageMapper
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
        $this->merchantProductStorageMapper = $merchantProductStorageMapper;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer|null
     */
    public function findOne(int $idProductAbstract): ?MerchantProductStorageTransfer
    {
        $productStorageData = $this->productStorageClient
            ->getProductAbstractStorageData($idProductAbstract, $this->localeClient->getCurrentLocale());

        if (!$productStorageData) {
            return null;
        }

        return $this->merchantProductStorageMapper->mapProductStorageDataToMerchantProductStorageTransfer(
            $productStorageData,
            new MerchantProductStorageTransfer()
        );
    }
}
