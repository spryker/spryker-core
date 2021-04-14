<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;

class ProductConcreteEditFormDataProvider implements ProductConcreteEditFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantProductFacade = $merchantProductFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return mixed[]
     */
    public function getData(int $idProductConcrete): array
    {
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchantOrFail();
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $this->merchantProductFacade->findProductConcrete(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->addIdProductConcrete($idProductConcrete)
        );

        return [
            ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE => $productConcreteTransfer,
            ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_PRICES => !(bool)$productConcreteTransfer->getPrices()->count(),
        ];
    }

    /**
     * @return int[][]
     */
    public function getOptions(): array
    {
        return [
            ProductConcreteEditForm::OPTION_SEARCHABILITY_CHOICES => array_flip($this->localeFacade->getAvailableLocales()),
        ];
    }
}
