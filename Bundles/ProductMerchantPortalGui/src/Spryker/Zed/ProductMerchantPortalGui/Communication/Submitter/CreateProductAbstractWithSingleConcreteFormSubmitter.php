<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Submitter;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;
use Symfony\Component\Form\FormInterface;

class CreateProductAbstractWithSingleConcreteFormSubmitter implements CreateProductAbstractWithSingleConcreteFormSubmitterInterface
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
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface
     */
    protected $localeDataProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface $localeDataProvider
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade,
        LocaleDataProviderInterface $localeDataProvider
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
        $this->localeDataProvider = $localeDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractWithSingleConcreteForm
     *
     * @return int
     */
    public function executeFormSubmission(FormInterface $createProductAbstractWithSingleConcreteForm): int
    {
        $formData = $createProductAbstractWithSingleConcreteForm->getData();
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setSku($formData[CreateProductAbstractWithSingleConcreteForm::FIELD_SKU])
            ->setName($formData[CreateProductAbstractWithSingleConcreteForm::FIELD_NAME])
            ->setIdMerchant($merchantUserTransfer->getIdMerchantOrFail());

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setName($formData[CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_NAME])
            ->setSku($formData[CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_SKU])
            ->setIsActive(false);

        $defaultStoreDefaultLocale = $this->localeDataProvider->findDefaultStoreDefaultLocale();
        foreach ($localeTransfers as $localeTransfer) {
            $productAbstractLocalizedName = $localeTransfer->getLocaleNameOrFail() === $defaultStoreDefaultLocale
                ? $formData[CreateProductAbstractWithSingleConcreteForm::FIELD_NAME]
                : '';
            $productConcreteLocalizedName = $localeTransfer->getLocaleNameOrFail() === $defaultStoreDefaultLocale
                ? $formData[CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_NAME]
                : '';
            $productAbstractTransfer->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())->setLocale($localeTransfer)->setName($productAbstractLocalizedName)
            );
            $productConcreteTransfer->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())->setLocale($localeTransfer)->setName($productConcreteLocalizedName)
            );
        }

        return $this->productFacade->addProduct($productAbstractTransfer, [$productConcreteTransfer]);
    }
}
