<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStorageClientInterface;
use Spryker\Shared\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConstants;

class PriceProductMerchantRelationshipAbstractReader implements PriceProductMerchantRelationshipAbstractReaderInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipKeyGeneratorInterface
     */
    protected $priceStorageKeyGenerator;

    /**
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipKeyGeneratorInterface $priceStorageKeyGenerator
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageToStorageClientInterface $storageClient,
        PriceProductMerchantRelationshipKeyGeneratorInterface $priceStorageKeyGenerator
    ) {
        $this->storageClient = $storageClient;
        $this->priceStorageKeyGenerator = $priceStorageKeyGenerator;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceMerchantRelationshipAbstract(int $idProductAbstract, int $idMerchantRelationship): ?PriceProductStorageTransfer
    {
        $key = $this->priceStorageKeyGenerator->generateKey(
            PriceProductMerchantRelationshipStorageConstants::PRICE_PRODUCT_ABSTRACT_MERCHANT_RELATIONSHIP_RESOURCE_NAME,
            $idProductAbstract,
            $idMerchantRelationship
        );

        return $this->findPriceProductStorageTransfer($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    protected function findPriceProductStorageTransfer(string $key): ?PriceProductStorageTransfer
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
