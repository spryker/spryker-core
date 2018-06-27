<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConstants;
use Spryker\Shared\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConstants;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToStoreFacadeInterface;

class PriceGrouper implements PriceGrouperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface $priceProductFacade,
        PriceProductMerchantRelationshipStorageToStoreFacadeInterface $storeFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
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
            ->setType(PriceProductMerchantRelationshipConstants::PRICE_DIMENSION_MERCHANT_RELATIONSHIP);

        $groupedPrices = [];
        foreach ($products as $product) {
            $idProduct = $product[$productPrimaryIdentifier];
            $idMerchantRelationship = $product[SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP];
            $idCompanyBusinessUnit = $product[MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT];

            if (!$idMerchantRelationship || !$idProduct) {
                continue;
            }

            $priceProductDimensionTransfer->setIdMerchantRelationship($idMerchantRelationship);
            $storeTransfer = $this->storeFacade->getStoreById($product[PriceProductMerchantRelationshipStorageConstants::COL_PRICE_PRODUCT_STORE_FK_STORE]);
            $prices = $this->priceProductFacade->findPricesBySkuGroupedForCurrentStore(
                $product[$productSkuIdentifier],
                $priceProductDimensionTransfer
            );

            $groupedPrices[] = (new PriceProductMerchantRelationshipStorageTransfer())
                ->setStoreName($storeTransfer->getName())
                ->setIdCompanyBusinessUnit($idCompanyBusinessUnit)
                ->setIdMerchantRelationship($idMerchantRelationship)
                ->setIdProduct($idProduct)
                ->setPrices($prices);
        }

        return $groupedPrices;
    }
}
