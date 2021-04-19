<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Submitter;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithMultiConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\SuperAttributeForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;
use Symfony\Component\Form\FormInterface;

class CreateProductAbstractWithMultiConcreteFormSubmitter implements CreateProductAbstractWithMultiConcreteFormSubmitterInterface
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
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface
     */
    protected $localeDataProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface $localeDataProvider
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade,
        LocaleDataProviderInterface $localeDataProvider
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
        $this->productAttributeFacade = $productAttributeFacade;
        $this->localeDataProvider = $localeDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractWithMultiConcreteForm
     *
     * @return int
     */
    public function executeFormSubmission(FormInterface $createProductAbstractWithMultiConcreteForm): int
    {
        $formData = $createProductAbstractWithMultiConcreteForm->getData();
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setSku($formData[CreateProductAbstractWithMultiConcreteForm::FIELD_SKU])
            ->setName($formData[CreateProductAbstractWithMultiConcreteForm::FIELD_NAME])
            ->setIdMerchant($merchantUserTransfer->getIdMerchantOrFail());

        $concreteProducts = $productAbstractTransfer[CreateProductAbstractWithMultiConcreteForm::FIELD_CONCRETE_PRODUCTS];

        $concreteProductTransfers = [];
        foreach ($concreteProducts as $concreteProduct) {
            $concreteProductTransfer = (new ProductConcreteTransfer())
                ->setSku($concreteProduct[ProductConcreteForm::FIELD_SKU])
                ->setName($concreteProduct[ProductConcreteForm::FIELD_NAME]);

            $attributes = $this->reformatSuperAttributes($concreteProductTransfer);
            $productManagementAttributeTransfers = $this->getProductManagementAttributes($attributes);

            foreach ($localeTransfers as $localeTransfer) {
                $localizedAttributes = $this->extractLocalizedAttributes(
                    $productManagementAttributeTransfers->getArrayCopy(),
                    $attributes,
                    $localeTransfer
                );

                $concreteProductTransfer->addLocalizedAttributes(
                    (new LocalizedAttributesTransfer())
                        ->setName($concreteProduct[ProductConcreteForm::FIELD_NAME])
                        ->setLocale($localeTransfer)
                        ->setAttributes($localizedAttributes)
                );
            }

            $concreteProductTransfers[] = $concreteProductTransfer;
        }

        return $this->productFacade->addProduct($productAbstractTransfer, $concreteProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $concreteProductTransfer
     *
     * @return string[]
     */
    protected function reformatSuperAttributes(ProductConcreteTransfer $concreteProductTransfer): array
    {
        $attributes = [];
        foreach ($concreteProductTransfer as $superAttribute) {
            $attributes[$superAttribute[SuperAttributeForm::FIELD_KEY]] = $superAttribute[SuperAttributeForm::FIELD_VALUE];
        }

        return $attributes;
    }

    /**
     * @param array $attributes
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function getProductManagementAttributes(array $attributes): ArrayObject
    {
        $productManagementAttributeFilterTransfer = new ProductManagementAttributeFilterTransfer();
        $productManagementAttributeFilterTransfer->setKeys(array_keys($attributes));

        return $this->productAttributeFacade
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer)
            ->getProductManagementAttributes();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     * @param string[] $attributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    protected function extractLocalizedAttributes(
        array $productManagementAttributeTransfers,
        array $attributes,
        LocaleTransfer $localeTransfer
    ): array {
        $localizedAttributes = [];

        foreach ($attributes as $attributeKey => $attributeValue) {
            $productManagementAttributeValueTransfer = $this->extractProductManagementAttributeValueTransfer(
                $attributeKey,
                $attributeValue,
                $productManagementAttributeTransfers
            );

            if (!$productManagementAttributeValueTransfer) {
                continue;
            }

            foreach ($productManagementAttributeValueTransfer->getLocalizedValues() as $attributeValueTranslationTransfer) {
                if ($attributeValueTranslationTransfer->getLocaleName() === $localeTransfer->getLocaleName()) {
                    $localizedAttributes[$attributeKey] = $attributeValueTranslationTransfer->getTranslation();
                }
            }
        }

        return $localizedAttributes;
    }

    /**
     * @param string $attributeKey
     * @param string $attributeValue
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer|null
     */
    protected function extractProductManagementAttributeValueTransfer(
        string $attributeKey,
        string $attributeValue,
        array $productManagementAttributeTransfers
    ): ?ProductManagementAttributeValueTransfer {
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            foreach ($productManagementAttributeTransfer->getValues() as $productManagementAttributeValueTransfer) {
                if (
                    $attributeKey === $productManagementAttributeTransfer->getKey()
                    && $attributeValue === $productManagementAttributeValueTransfer->getValue()
                ) {
                    return $productManagementAttributeValueTransfer;
                }
            }
        }

        return null;
    }
}
