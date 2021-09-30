<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface;

abstract class AbstractPriceProductMerchantRelationshipStorageWriter
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface
     */
    protected $priceProductMerchantRelationshipStorageEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface
     */
    protected $priceProductMerchantRelationshipStorageRepository;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface
     */
    protected $priceGrouper;

    /**
     * @var array<\Spryker\Zed\PriceProductMerchantRelationshipStorageExtension\Dependency\Plugin\PriceProductMerchantRelationshipStorageFilterPluginInterface>
     */
    protected $priceProductMerchantRelationshipStorageFilterPlugins;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface $priceProductMerchantRelationshipStorageEntityManager
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface $priceProductMerchantRelationshipStorageRepository
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface $priceGrouper
     * @param array<\Spryker\Zed\PriceProductMerchantRelationshipStorageExtension\Dependency\Plugin\PriceProductMerchantRelationshipStorageFilterPluginInterface> $priceProductMerchantRelationshipStorageFilterPlugins
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageEntityManagerInterface $priceProductMerchantRelationshipStorageEntityManager,
        PriceProductMerchantRelationshipStorageRepositoryInterface $priceProductMerchantRelationshipStorageRepository,
        PriceGrouperInterface $priceGrouper,
        array $priceProductMerchantRelationshipStorageFilterPlugins
    ) {
        $this->priceProductMerchantRelationshipStorageEntityManager = $priceProductMerchantRelationshipStorageEntityManager;
        $this->priceProductMerchantRelationshipStorageRepository = $priceProductMerchantRelationshipStorageRepository;
        $this->priceGrouper = $priceGrouper;
        $this->priceProductMerchantRelationshipStorageFilterPlugins = $priceProductMerchantRelationshipStorageFilterPlugins;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer> $priceProductMerchantRelationshipStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    protected function executePriceProductMerchantRelationshipStorageFilterPlugins(array $priceProductMerchantRelationshipStorageTransfers): array
    {
        foreach ($this->priceProductMerchantRelationshipStorageFilterPlugins as $priceProductMerchantRelationshipStorageFilterPlugin) {
            $priceProductMerchantRelationshipStorageTransfers = $priceProductMerchantRelationshipStorageFilterPlugin->filter($priceProductMerchantRelationshipStorageTransfers);
        }

        return $priceProductMerchantRelationshipStorageTransfers;
    }
}
