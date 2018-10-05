<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Zed\ProductSetGui\Communication\Form\CreateProductSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\General\GeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\General\LocalizedGeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\ImagesFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\LocalizedProductImageSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\ProductImageFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Products\UpdateProductsFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Seo\LocalizedSeoFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Seo\SeoFormType;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductSetInterface;
use Spryker\Zed\ProductSetGui\ProductSetGuiConfig;

class UpdateFormDataProvider extends AbstractProductSetFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductSetInterface
     */
    protected $productSetFacade;

    /**
     * @var \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductSetInterface $productSetFacade
     * @param \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductSetGui\ProductSetGuiConfig $productSetGuiConfig
     */
    public function __construct(
        ProductSetGuiToProductSetInterface $productSetFacade,
        ProductSetGuiToLocaleInterface $localeFacade,
        ProductSetGuiConfig $productSetGuiConfig
    ) {
        parent::__construct($productSetGuiConfig);

        $this->localeFacade = $localeFacade;
        $this->productSetFacade = $productSetFacade;
    }

    /**
     * @param int $idProductSet
     *
     * @return array
     */
    public function getData($idProductSet)
    {
        $productSetTransfer = new ProductSetTransfer();
        $productSetTransfer->setIdProductSet($idProductSet);
        $productSetTransfer = $this->productSetFacade->findProductSet($productSetTransfer);

        $data = [
            CreateProductSetFormType::FIELD_GENERAL_FORM => $this->getGeneralFormData($productSetTransfer),
            CreateProductSetFormType::FIELD_PRODUCTS_FORM => $this->getProductsFormData($productSetTransfer),
            CreateProductSetFormType::FIELD_SEO_FORM => $this->getSeoFormData($productSetTransfer),
            CreateProductSetFormType::FIELD_IMAGES_FORM => $this->getImagesFormData($productSetTransfer),
        ];

        return $data;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return array
     */
    protected function getGeneralFormData(ProductSetTransfer $productSetTransfer)
    {
        return [
            GeneralFormType::FIELD_ID_PRODUCT_SET => $productSetTransfer->getIdProductSet(),
            GeneralFormType::FIELD_LOCALIZED_GENERAL_FORM_COLLECTION => $this->getLocalizedGeneralFormCollectionData($productSetTransfer),
            GeneralFormType::FIELD_IS_ACTIVE => $productSetTransfer->getIsActive(),
            GeneralFormType::FIELD_WEIGHT => $productSetTransfer->getWeight(),
            GeneralFormType::FIELD_PRODUCT_SET_KEY => $productSetTransfer->getProductSetKey(),
            GeneralFormType::FIELD_PRODUCT_SET_KEY_ORIGINAL => $productSetTransfer->getProductSetKey(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return array
     */
    protected function getProductsFormData(ProductSetTransfer $productSetTransfer)
    {
        return [
            UpdateProductsFormType::FIELD_ID_PRODUCT_ABSTRACTS => $productSetTransfer->getIdProductAbstracts(),
            UpdateProductsFormType::FIELD_PRODUCT_POSITION => array_flip($productSetTransfer->getIdProductAbstracts()),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return array
     */
    protected function getLocalizedGeneralFormCollectionData(ProductSetTransfer $productSetTransfer)
    {
        $result = [];
        $localeCollection = $this->localeFacade->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $localizedProductSetTransfer = $this->getLocalizeDataTransfer($productSetTransfer, $localeTransfer->getIdLocale());

            if (!$localizedProductSetTransfer) {
                continue;
            }

            $result[] = [
                LocalizedGeneralFormType::FIELD_FK_LOCALE => $localeTransfer->getIdLocale(),
                LocalizedGeneralFormType::FIELD_NAME => $localizedProductSetTransfer->getProductSetData()->getName(),
                LocalizedGeneralFormType::FIELD_URL => $localizedProductSetTransfer->getUrl(),
                LocalizedGeneralFormType::FIELD_URL_PREFIX => $this->getUrlPrefix($localeTransfer),
                LocalizedGeneralFormType::FIELD_ORIGINAL_URL => $localizedProductSetTransfer->getUrl(),
                LocalizedGeneralFormType::FIELD_DESCRIPTION => $localizedProductSetTransfer->getProductSetData()->getDescription(),
            ];
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return array
     */
    protected function getSeoFormData(ProductSetTransfer $productSetTransfer)
    {
        return [
            SeoFormType::FIELD_LOCALIZED_SEO_FORM_COLLECTION => $this->getLocalizedSeoFormCollectionData($productSetTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return array
     */
    protected function getLocalizedSeoFormCollectionData(ProductSetTransfer $productSetTransfer)
    {
        $result = [];
        $localeCollection = $this->localeFacade->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $localizedProductSetTransfer = $this->getLocalizeDataTransfer($productSetTransfer, $localeTransfer->getIdLocale());

            if (!$localizedProductSetTransfer) {
                continue;
            }

            $result[] = [
                LocalizedSeoFormType::FIELD_FK_LOCALE => $localeTransfer->getIdLocale(),
                LocalizedSeoFormType::FIELD_META_TITLE => $localizedProductSetTransfer->getProductSetData()->getMetaTitle(),
                LocalizedSeoFormType::FIELD_META_KEYWORDS => $localizedProductSetTransfer->getProductSetData()->getMetaKeywords(),
                LocalizedSeoFormType::FIELD_META_DESCRIPTION => $localizedProductSetTransfer->getProductSetData()->getMetaDescription(),
            ];
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer|null
     */
    protected function getLocalizeDataTransfer(ProductSetTransfer $productSetTransfer, $idLocale)
    {
        foreach ($productSetTransfer->getLocalizedData() as $localizedProductSetTransfer) {
            if ($localizedProductSetTransfer->getLocale()->getIdLocale() === $idLocale) {
                return $localizedProductSetTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return array
     */
    protected function getImagesFormData(ProductSetTransfer $productSetTransfer)
    {
        $results = [];
        $results[ImagesFormType::getImageSetFormName()] = $this->getImageSetFormData($productSetTransfer);

        $localeCollection = $this->localeFacade->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $results[ImagesFormType::getImageSetFormName($localeTransfer->getLocaleName())] = $this->getImageSetFormData($productSetTransfer, $localeTransfer);
        }

        return $results;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    protected function getImageSetFormData(ProductSetTransfer $productSetTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        $idLocale = $localeTransfer ? $localeTransfer->getIdLocale() : null;
        $productImageSetTransfers = $this->getLocalizeImageSetTransfers($productSetTransfer, $idLocale);

        return $this->getImageSetData($productImageSetTransfers, $idLocale);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param int|null $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getLocalizeImageSetTransfers(ProductSetTransfer $productSetTransfer, $idLocale = null)
    {
        $imageSets = [];
        foreach ($productSetTransfer->getImageSets() as $productImageSetTransfer) {
            if ($productImageSetTransfer->getLocale() === null) {
                if (!$idLocale) {
                    $imageSets[] = $productImageSetTransfer;
                }

                continue;
            }

            if ($productImageSetTransfer->getLocale()->getIdLocale() === $idLocale) {
                $imageSets[] = $productImageSetTransfer;
            }
        }

        return $imageSets;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     * @param int|null $idLocale
     *
     * @return array
     */
    protected function getImageSetData(array $productImageSetTransfers, $idLocale = null)
    {
        $result = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $productImageCollection = [];
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $productImageData = $productImageTransfer->toArray();
                $productImageData = $this->setImagePreviewData($productImageTransfer, $productImageData);
                $productImageCollection[] = $productImageData;
            }

            $result[] = [
                LocalizedProductImageSetFormType::FIELD_FK_LOCALE => $idLocale,
                LocalizedProductImageSetFormType::FIELD_NAME => $productImageSetTransfer->getName(),
                LocalizedProductImageSetFormType::FIELD_ID_PRODUCT_IMAGE_SET => $productImageSetTransfer->getIdProductImageSet(),
                LocalizedProductImageSetFormType::FIELD_PRODUCT_IMAGE_COLLECTION => $productImageCollection,
            ];
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     * @param array $productImageData
     *
     * @return array
     */
    protected function setImagePreviewData(ProductImageTransfer $productImageTransfer, array $productImageData)
    {
        $productImageData[ProductImageFormType::FIELD_IMAGE_PREVIEW] = $productImageTransfer->getExternalUrlSmall();
        $productImageData[ProductImageFormType::FIELD_IMAGE_PREVIEW_LARGE_URL] = $productImageTransfer->getExternalUrlLarge();

        return $productImageData;
    }
}
