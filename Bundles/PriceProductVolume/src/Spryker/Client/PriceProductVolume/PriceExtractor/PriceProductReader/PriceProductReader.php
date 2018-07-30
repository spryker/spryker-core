<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductVolume\PriceExtractor\PriceProductReader;

use Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToLocaleClientInterface;
use Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToPriceProductStorageClientInterface;
use Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToProductStorageClientInterface;

class PriceProductReader implements PriceProductReaderInterface
{
    protected const STORAGE_KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @param \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToLocaleClientInterface $localeClient
     * @param \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToPriceProductStorageClientInterface $priceProductStorageClient
     */
    public function __construct(
        PriceProductVolumeToLocaleClientInterface $localeClient,
        PriceProductVolumeToProductStorageClientInterface $productStorageClient,
        PriceProductVolumeToPriceProductStorageClientInterface $priceProductStorageClient
    ) {
        $this->localeClient = $localeClient;
        $this->productStorageClient = $productStorageClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductAbstractFromPriceProduct(int $idProductConcrete): array
    {
        $idProductAbstract = $this->findIdProductAbstractByIdProductConcrete($idProductConcrete);

        if (!$idProductAbstract) {
            return [];
        }

        return $this->priceProductStorageClient->getPriceProductAbstractTransfers($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int|null
     */
    protected function findIdProductAbstractByIdProductConcrete(int $idProductConcrete): ?int
    {
        $localeName = $this->localeClient->getCurrentLocale();
        $productData = $this->productStorageClient->getProductConcreteStorageData(
            $idProductConcrete,
            $localeName
        );

        if (isset($productData[static::STORAGE_KEY_ID_PRODUCT_ABSTRACT]) && $productData[static::STORAGE_KEY_ID_PRODUCT_ABSTRACT]) {
            return $productData[static::STORAGE_KEY_ID_PRODUCT_ABSTRACT];
        }

        return null;
    }
}
