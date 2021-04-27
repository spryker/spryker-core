<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithMultiConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Symfony\Component\Form\FormInterface;

class ProductAbstractMapper implements ProductAbstractMapperInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface
     */
    protected $localeDataProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface $localeDataProvider
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        LocaleDataProviderInterface $localeDataProvider
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
        $this->localeDataProvider = $localeDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractWithMultiConcreteForm
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapFormDataToProductAbstractTransfer(
        FormInterface $createProductAbstractWithMultiConcreteForm
    ): ProductAbstractTransfer {
        $formData = $createProductAbstractWithMultiConcreteForm->getData();
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setSku($formData[CreateProductAbstractWithMultiConcreteForm::FIELD_SKU])
            ->setName($formData[CreateProductAbstractWithMultiConcreteForm::FIELD_NAME])
            ->setIdMerchant($merchantUserTransfer->getIdMerchantOrFail());

        $defaultStoreDefaultLocale = $this->localeDataProvider->findDefaultStoreDefaultLocale();
        foreach ($localeTransfers as $localeTransfer) {
            $productAbstractLocalizedName = $localeTransfer->getLocaleNameOrFail() === $defaultStoreDefaultLocale
                ? $formData[CreateProductAbstractWithSingleConcreteForm::FIELD_NAME]
                : '';
            $productAbstractTransfer->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())->setLocale($localeTransfer)->setName($productAbstractLocalizedName)
            );
        }

        return $productAbstractTransfer;
    }
}
