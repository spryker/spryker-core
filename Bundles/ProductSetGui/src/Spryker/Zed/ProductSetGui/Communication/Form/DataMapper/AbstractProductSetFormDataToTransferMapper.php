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
use Spryker\Zed\ProductSetGui\Communication\Form\Seo\SeoFormType;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface;
use Symfony\Component\Form\FormInterface;

abstract class AbstractProductSetFormDataToTransferMapper
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
        $productSetTransfer = $this->mapProductSetGeneralData($productSetForm);
        $productSetTransfer = $this->mapProductAbstractIds($productSetTransfer, $productSetForm);
        $productSetTransfer = $this->mapSeoData($productSetTransfer, $productSetForm);
        $productSetTransfer = $this->mapProductImageSets($productSetTransfer, $productSetForm);

        return $productSetTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productSetForm
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function mapProductSetGeneralData(FormInterface $productSetForm)
    {
        $productSetTransfer = new ProductSetTransfer();

        $generalData = $productSetForm->get($this->getGeneralFormFieldName())->getData();
        $productSetTransfer->fromArray($generalData, true);

        $this->mapLocalizedGeneralData($productSetTransfer, $productSetForm);

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Symfony\Component\Form\FormInterface $productSetForm
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function mapProductAbstractIds(ProductSetTransfer $productSetTransfer, FormInterface $productSetForm)
    {
        $idProductAbstracts = $productSetForm->get($this->getProductFormFieldName())
            ->getData()[$this->getIdProductAbstractFieldName()];

        $productSetTransfer->setIdProductAbstracts($idProductAbstracts);

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Symfony\Component\Form\FormInterface $productSetForm
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function mapSeoData(ProductSetTransfer $productSetTransfer, FormInterface $productSetForm)
    {
        $localizedSeoFormDataCollection = $productSetForm->get($this->getSeoFormFieldName())
            ->get(SeoFormType::FIELD_LOCALIZED_SEO_FORM_COLLECTION)
            ->getData();

        foreach ($localizedSeoFormDataCollection as $i => $localizedSeoFormData) {
            $productSetDataTransfer = $productSetTransfer->getLocalizedData()[$i]->getProductSetData();
            $productSetDataTransfer->fromArray($localizedSeoFormData, true);
        }

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Symfony\Component\Form\FormInterface $productSetForm
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function mapProductImageSets(ProductSetTransfer $productSetTransfer, FormInterface $productSetForm)
    {
        $defaultImageSetFormCollection = $productSetForm->get($this->getImagesFormFieldName())
            ->get(ImagesFormType::getImageSetFormName());

        $productSetTransfer->setImageSets(new ArrayObject());
        $productSetTransfer = $this->setProductImageSets($defaultImageSetFormCollection, $productSetTransfer);

        $localeCollection = $this->localeFacade->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedImageSetFormCollection = $productSetForm->get($this->getImagesFormFieldName())
                ->get(ImagesFormType::getImageSetFormName($localeTransfer->getLocaleName()));

            $this->setProductImageSets($localizedImageSetFormCollection, $productSetTransfer, $localeTransfer);
        }

        return $productSetTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $imageSetFormCollection
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function setProductImageSets(FormInterface $imageSetFormCollection, ProductSetTransfer $productSetTransfer, ?LocaleTransfer $localeTransfer = null)
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

            $result[] = $imageTransfer;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Symfony\Component\Form\FormInterface $productSetForm
     *
     * @return void
     */
    protected function mapLocalizedGeneralData(ProductSetTransfer $productSetTransfer, FormInterface $productSetForm)
    {
        $localizedGeneralFormDataCollection = $productSetForm->get($this->getGeneralFormFieldName())
            ->get(GeneralFormType::FIELD_LOCALIZED_GENERAL_FORM_COLLECTION)
            ->getData();

        foreach ($localizedGeneralFormDataCollection as $localizedGeneralFormData) {
            $localizedProductSetTransfer = new LocalizedProductSetTransfer();
            $localizedProductSetTransfer->fromArray($localizedGeneralFormData, true);

            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setIdLocale($localizedGeneralFormData[LocalizedGeneralFormType::FIELD_FK_LOCALE]);
            $localizedProductSetTransfer->setLocale($localeTransfer);

            $productSetDataTransfer = new ProductSetDataTransfer();
            $productSetDataTransfer->fromArray($localizedGeneralFormData, true);
            $localizedProductSetTransfer->setProductSetData($productSetDataTransfer);

            $productSetTransfer->addLocalizedData($localizedProductSetTransfer);
        }
    }

    /**
     * @return string
     */
    abstract protected function getGeneralFormFieldName();

    /**
     * @return string
     */
    abstract protected function getProductFormFieldName();

    /**
     * @return string
     */
    abstract protected function getSeoFormFieldName();

    /**
     * @return string
     */
    abstract protected function getImagesFormFieldName();

    /**
     * @return string
     */
    abstract protected function getIdProductAbstractFieldName();
}
