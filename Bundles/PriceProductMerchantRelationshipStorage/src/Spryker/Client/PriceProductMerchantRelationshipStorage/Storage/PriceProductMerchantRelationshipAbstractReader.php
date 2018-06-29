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
     * @var \Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMapper
     */
    protected $priceProductMapper;

    /**
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipKeyGeneratorInterface $priceStorageKeyGenerator
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMapperInterface $priceProductMapper
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageToStorageClientInterface $storageClient,
        PriceProductMerchantRelationshipKeyGeneratorInterface $priceStorageKeyGenerator,
        PriceProductMapperInterface $priceProductMapper
    ) {
        $this->storageClient = $storageClient;
        $this->priceStorageKeyGenerator = $priceStorageKeyGenerator;
        $this->priceProductMapper = $priceProductMapper;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPrices(int $idProductAbstract, int $idCompanyBusinessUnit): array
    {
        $key = $this->priceStorageKeyGenerator->generateKey(
            PriceProductMerchantRelationshipStorageConstants::PRICE_PRODUCT_ABSTRACT_MERCHANT_RELATIONSHIP_RESOURCE_NAME,
            $idProductAbstract,
            $idCompanyBusinessUnit
        );

        return $this->findPriceProductTransfers($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findPriceProductTransfers(string $key): array
    {
        $priceData = $this->storageClient->get($key);

        if (!$priceData) {
            return [];
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $priceProductStorageTransfer->fromArray($priceData, true);

        return $this->priceProductMapper->mapPriceProductStorageTransferToPriceProductTransfers($priceProductStorageTransfer);
    }
}
