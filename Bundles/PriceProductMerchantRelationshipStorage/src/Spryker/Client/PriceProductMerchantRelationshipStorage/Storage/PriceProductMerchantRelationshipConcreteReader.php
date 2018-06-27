<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStorageClientInterface;
use Spryker\Shared\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConstants;

class PriceProductMerchantRelationshipConcreteReader implements PriceProductMerchantRelationshipConcreteReaderInterface
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
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPriceMerchantRelationshipConcrete(int $idProductAbstract, int $idCompanyBusinessUnit): array
    {
        $key = $this->priceStorageKeyGenerator->generateKey(
            PriceProductMerchantRelationshipStorageConstants::PRICE_PRODUCT_CONCRETE_MERCHANT_RELATIONSHIP_RESOURCE_NAME,
            $idProductAbstract,
            $idCompanyBusinessUnit
        );

        return $this->findPriceProductStorageTransfer($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findPriceProductStorageTransfer(string $key): array
    {
        $priceData = $this->storageClient->get($key);

        if (!$priceData) {
            return [];
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $priceProductStorageTransfer->fromArray($priceData, true);

        return (new PriceProductMapper())->mapPriceProductStorageTransferToPriceProductTransfers($priceProductStorageTransfer);
    }
}
