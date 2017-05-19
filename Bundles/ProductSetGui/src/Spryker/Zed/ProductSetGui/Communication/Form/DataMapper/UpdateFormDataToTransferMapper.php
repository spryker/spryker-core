<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataMapper;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductSetDataTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Zed\ProductSetGui\Communication\Form\General\GeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\General\LocalizedGeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\ImagesFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\LocalizedProductImageSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Products\UpdateProductsFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Seo\SeoFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\UpdateProductSetFormType;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface;
use Symfony\Component\Form\FormInterface;

class UpdateFormDataToTransferMapper
{

    /**
     * @var \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface $localeFacade
     */
    public function __construct(ProductSetGuiToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productSetForm
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function mapData(FormInterface $productSetForm)
    {
        $productSetTransfer = new ProductSetTransfer();

        $generalData = $productSetForm->get(UpdateProductSetFormType::FIELD_GENERAL_FORM)->getData();

        // product set
        $productSetTransfer->fromArray($generalData, true);

        // general data
        $localizedGeneralFormDataCollection = $productSetForm->get(UpdateProductSetFormType::FIELD_GENERAL_FORM)
            ->get(GeneralFormType::FIELD_LOCALIZED_GENERAL_FORM_COLLECTION)
            ->getData();

        foreach ($localizedGeneralFormDataCollection as $localizedGeneralFormData) {
            // localized product set
            $localizedProductSetTransfer = new LocalizedProductSetTransfer();
            $localizedProductSetTransfer->fromArray($localizedGeneralFormData, true);

            // locale
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setIdLocale($localizedGeneralFormData[LocalizedGeneralFormType::FIELD_FK_LOCALE]);
            $localizedProductSetTransfer->setLocale($localeTransfer);

            // product set data
            $productSetDataTransfer = new ProductSetDataTransfer();
            $productSetDataTransfer->fromArray($localizedGeneralFormData, true);
            $localizedProductSetTransfer->setProductSetData($productSetDataTransfer);

            $productSetTransfer->addLocalizedData($localizedProductSetTransfer);
        }

        // products
        $idProductAbstracts = $productSetForm->get(UpdateProductSetFormType::FIELD_PRODUCTS_FORM)
            ->getData()[UpdateProductsFormType::FIELD_ID_PRODUCT_ABSTRACTS];
        $productSetTransfer->setIdProductAbstracts($idProductAbstracts);

        // seo
        $localizedSeoFormDataCollection = $productSetForm->get(UpdateProductSetFormType::FIELD_SEO_FORM)
            ->get(SeoFormType::FIELD_LOCALIZED_SEO_FORM_COLLECTION)
            ->getData();

        foreach ($localizedSeoFormDataCollection as $i => $localizedSeoFormData) {
            $productSetDataTransfer = $productSetTransfer->getLocalizedData()[$i]->getProductSetData();
            $productSetDataTransfer->fromArray($localizedSeoFormData, true);
        }

        // images
        $defaultImageSetFormCollection = $productSetForm->get(UpdateProductSetFormType::FIELD_IMAGES_FORM)
            ->get(ImagesFormType::getImageSetFormName());
        $this->setProductImageSets($defaultImageSetFormCollection, $productSetTransfer);

        $localeCollection = $this->localeFacade->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedImageSetFormCollection = $productSetForm->get(UpdateProductSetFormType::FIELD_IMAGES_FORM)
                ->get(ImagesFormType::getImageSetFormName($localeTransfer->getLocaleName()));
            $this->setProductImageSets($localizedImageSetFormCollection, $productSetTransfer, $localeTransfer);
        }

        return $productSetTransfer;
    }

    /**
     * @param array $imageCollectionData
     *
     * @return array
     */
    public function mapProductImageCollection(array $imageCollectionData)
    {
        $result = [];

        foreach ($imageCollectionData as $i => $imageData) {
            $imageTransfer = new ProductImageTransfer();
            $imageTransfer->fromArray($imageData, true);
            $imageTransfer->setSortOrder($i);

            $result[] = $imageTransfer;
        }

        return $result;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $imageSetFormCollection
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    protected function setProductImageSets(FormInterface $imageSetFormCollection, ProductSetTransfer $productSetTransfer, LocaleTransfer $localeTransfer = null)
    {
        foreach ($imageSetFormCollection as $imageSetForm) {
            $imageSetFormData = $imageSetForm->getData();
            $imageSetData = array_filter($imageSetFormData);

            $imageSetTransfer = new ProductImageSetTransfer();
            $imageSetTransfer->fromArray($imageSetData, true);
            $imageSetTransfer->setLocale($localeTransfer);

            $productImages = $this->mapProductImageCollection(
                $imageSetForm->get(LocalizedProductImageSetFormType::FIELD_PRODUCT_IMAGE_COLLECTION)->getData()
            );
            if ($productImages) {
                $imageSetTransfer->setProductImages(new ArrayObject($productImages));
                $productSetTransfer->addImageSet($imageSetTransfer);
            }
        }
    }

}
