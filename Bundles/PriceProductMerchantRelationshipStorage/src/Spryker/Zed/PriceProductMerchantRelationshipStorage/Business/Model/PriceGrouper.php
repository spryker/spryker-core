<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Spryker\Shared\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConstants;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig;

class PriceGrouper implements PriceGrouperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig $config
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface $priceProductFacade,
        PriceProductMerchantRelationshipStorageConfig $config
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->config = $config;
    }

    /**
     * @param array $products
     * @param string $productPrimaryIdentifier
     * @param string $productSkuIdentifier
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function getGroupedPrices(array $products, string $productPrimaryIdentifier, string $productSkuIdentifier): array
    {
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($this->config->getPriceDimensionMerchantRelationship());

        $groupedPrices = [];
        foreach ($products as $product) {
            $storeName = $product[PriceProductMerchantRelationshipStorageConstants::COL_STORE_NAME];
            $idProduct = $product[$productPrimaryIdentifier];
            $idMerchantRelationship = $product[PriceProductMerchantRelationshipStorageConstants::COL_FK_MERCHANT_RELATIONSHIP];
            $idCompanyBusinessUnit = $product[MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT];

            if (!$idMerchantRelationship || !$idProduct) {
                continue;
            }

            $priceProductDimensionTransfer->setIdMerchantRelationship($idMerchantRelationship);
            $prices = $this->priceProductFacade->findPricesBySkuGroupedForCurrentStore(
                $product[$productSkuIdentifier],
                $priceProductDimensionTransfer
            );

            $groupedPrices[] = (new PriceProductMerchantRelationshipStorageTransfer())
                ->setStoreName($storeName)
                ->setIdCompanyBusinessUnit($idCompanyBusinessUnit)
                ->setIdMerchantRelationship($idMerchantRelationship)
                ->setIdProduct($idProduct)
                ->setPrices($prices);
        }

        return $groupedPrices;
    }
}
