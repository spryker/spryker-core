<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;

class PriceConcreteStorageReader implements PriceConcreteStorageReaderInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGeneratorInterface
     */
    protected $priceStorageKeyGenerator;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface[]
     */
    protected $priceDimensionPlugins;

    /**
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface $storageClient
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGeneratorInterface $priceStorageKeyGenerator
     * @param \Spryker\Client\PriceProductStorage\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface[] $priceDimensionPlugins
     */
    public function __construct(
        PriceProductStorageToStorageInterface $storageClient,
        PriceProductStorageKeyGeneratorInterface $priceStorageKeyGenerator,
        array $priceDimensionPlugins
    ) {
        $this->storageClient = $storageClient;
        $this->priceStorageKeyGenerator = $priceStorageKeyGenerator;
        $this->priceDimensionPlugins = $priceDimensionPlugins;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceConcreteStorageTransfer($idProductConcrete)
    {
        $key = $this->priceStorageKeyGenerator->generateKey(PriceProductStorageConstants::PRICE_CONCRETE_RESOURCE_NAME, $idProductConcrete);

        return $this->findPriceProductStorageTransfer($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    protected function findPriceProductStorageTransfer($key)
    {
        $priceData = $this->storageClient->get($key);

        if (!$priceData) {
            return null;
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $priceProductStorageTransfer->fromArray($priceData, true);

        return $priceProductStorageTransfer;
    }
}
