<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;

class ProductAbstractFormDataProvider implements ProductAbstractFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade,
        ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->merchantProductFacade = $merchantProductFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstract(int $idProductAbstract, int $idMerchant): ?ProductAbstractTransfer
    {
        return $this->merchantProductFacade->findProductAbstract(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->setIdProductAbstract($idProductAbstract)
        );
    }

    /**
     * @return int[][]
     */
    public function getOptions(): array
    {
        return [
            ProductAbstractForm::OPTION_STORE_CHOICES => $this->getStoreChoices(),
        ];
    }

    /**
     * @return int[]
     */
    protected function getStoreChoices(): array
    {
        $storeChoices = [];

        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            /** @var int $idStore */
            $idStore = $storeTransfer->requireIdStore()->getIdStore();
            /** @var string $storeName */
            $storeName = $storeTransfer->requireName()->getName();
            $storeChoices[$storeName] = $idStore;
        }

        return $storeChoices;
    }
}
