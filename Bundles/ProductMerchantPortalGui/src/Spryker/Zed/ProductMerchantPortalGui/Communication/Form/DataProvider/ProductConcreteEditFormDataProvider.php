<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;

class ProductConcreteEditFormDataProvider implements ProductConcreteEditFormDataProviderInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::OPTION_SEARCHABILITY_CHOICES
     * @var string
     */
    protected const OPTION_SEARCHABILITY_CHOICES = 'OPTION_SEARCHABILITY_CHOICES';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_PRICES
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES = 'useAbstractProductPrices';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_NAME
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_NAME = 'useAbstractProductName';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_DESCRIPTION
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_DESCRIPTION = 'useAbstractProductDescription';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_IMAGE_SETS
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_IMAGE_SETS = 'useAbstractProductImageSets';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE = 'productConcrete';

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
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeDataProviderInterface
     */
    protected $productAttributeDataProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeDataProviderInterface $productAttributeDataProvider
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductAttributeDataProviderInterface $productAttributeDataProvider
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantProductFacade = $merchantProductFacade;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
        $this->productAttributeDataProvider = $productAttributeDataProvider;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array<mixed>
     */
    public function getData(int $idProductConcrete): array
    {
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchantOrFail();
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $this->merchantProductFacade->findProductConcrete(
            (new MerchantProductCriteriaTransfer())->setIdMerchant($idMerchant)->addIdProductConcrete($idProductConcrete)
        );
        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($productConcreteTransfer->getFkProductAbstractOrFail());

        $useAbstractProductName = $this->hasSameLocalizedAttributeNames(
            $productConcreteTransfer->getLocalizedAttributes(),
            $productAbstractTransfer->getLocalizedAttributes()
        );

        $useAbstractProductDescription = $this->hasSameLocalizedDescriptions(
            $productConcreteTransfer->getLocalizedAttributes(),
            $productAbstractTransfer->getLocalizedAttributes()
        );

        $useAbstractProductImageSets = $this->areProductImageSetTransfersEqual(
            $productConcreteTransfer->getImageSets(),
            $productAbstractTransfer->getImageSets()
        );

        return [
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE => $productConcreteTransfer,
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES => $productConcreteTransfer->getPrices()->count() === 0,
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_NAME => $useAbstractProductName,
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_DESCRIPTION => $useAbstractProductDescription,
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_IMAGE_SETS => $useAbstractProductImageSets,
        ];
    }

    /**
     * @return array<string[]>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_SEARCHABILITY_CHOICES => array_flip($this->localeFacade->getAvailableLocales()),
        ];
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\LocalizedAttributesTransfer> $productConcreteLocalizedAttributesTransfers
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\LocalizedAttributesTransfer> $productAbstractLocalizedAttributesTransfers
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $productConcreteLocalizedAttributesTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $productAbstractLocalizedAttributesTransfers
     *
     * @return bool
     */
    protected function hasSameLocalizedAttributeNames(
        ArrayObject $productConcreteLocalizedAttributesTransfers,
        ArrayObject $productAbstractLocalizedAttributesTransfers
    ): bool {
        foreach ($productConcreteLocalizedAttributesTransfers as $productConcreteLocalizedAttributesTransfer) {
            $productAbstractLocalizedAttributesTransfer = $this->productAttributeDataProvider->findLocalizedAttribute(
                $productAbstractLocalizedAttributesTransfers,
                $productConcreteLocalizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail()
            );

            if (!$productAbstractLocalizedAttributesTransfer) {
                return false;
            }

            if ($productConcreteLocalizedAttributesTransfer->getName() !== $productAbstractLocalizedAttributesTransfer->getName()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\LocalizedAttributesTransfer> $productConcreteAttributes
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\LocalizedAttributesTransfer> $productAbstractAttributes
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $productConcreteAttributes
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $productAbstractAttributes
     *
     * @return bool
     */
    protected function hasSameLocalizedDescriptions(ArrayObject $productConcreteAttributes, ArrayObject $productAbstractAttributes): bool
    {
        foreach ($productConcreteAttributes as $concreteLocalizedAttribute) {
            $abstractLocalizedAttribute = $this->productAttributeDataProvider->findLocalizedAttribute(
                $productAbstractAttributes,
                $concreteLocalizedAttribute->getLocaleOrFail()->getIdLocaleOrFail()
            );

            if (!$abstractLocalizedAttribute) {
                return false;
            }

            if ($concreteLocalizedAttribute->getDescription() !== $abstractLocalizedAttribute->getDescription()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfersToCompare
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfersToCompare
     *
     * @return bool
     */
    protected function areProductImageSetTransfersEqual(ArrayObject $productImageSetTransfers, ArrayObject $productImageSetTransfersToCompare): bool
    {
        $normalizedImageSets = $this->normalizeImageSets($productImageSetTransfers);
        $normalizedImageSetsToCompare = $this->normalizeImageSets($productImageSetTransfersToCompare);

        return serialize($normalizedImageSets) === serialize($normalizedImageSetsToCompare);
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     *
     * @return array
     */
    protected function normalizeImageSets(ArrayObject $productImageSetTransfers): array
    {
        $result = [];

        foreach ($productImageSetTransfers as $imageSetTransfer) {
            $imageSet = $imageSetTransfer->toArrayRecursiveCamelCased();

            $imageSet = $this->filterArray($imageSet, [ProductImageSetTransfer::NAME, ProductImageSetTransfer::PRODUCT_IMAGES]);

            foreach ($imageSet[ProductImageSetTransfer::PRODUCT_IMAGES] as $index => $productImage) {
                $imageSet[ProductImageSetTransfer::PRODUCT_IMAGES][$index] = $this->filterArray(
                    $productImage,
                    [
                        ProductImageTransfer::EXTERNAL_URL_LARGE,
                        ProductImageTransfer::EXTERNAL_URL_SMALL,
                        ProductImageTransfer::SORT_ORDER,
                    ]
                );
            }

            $result[] = $imageSet;
        }

        return $result;
    }

    /**
     * @param array $array
     * @param array<string> $exclusions
     *
     * @return array
     */
    protected function filterArray(array $array, array $exclusions): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (in_array($key, $exclusions)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
