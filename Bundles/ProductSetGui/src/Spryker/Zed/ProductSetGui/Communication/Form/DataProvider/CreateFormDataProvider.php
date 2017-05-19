<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductSetGui\Communication\Form\General\GeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\General\LocalizedGeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\ImagesFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\LocalizedProductImageSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\CreateProductSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Seo\SeoFormType;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface;

class CreateFormDataProvider
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
     * @return array
     */
    public function getData()
    {
        return [
            CreateProductSetFormType::FIELD_GENERAL_FORM => [
                GeneralFormType::FIELD_LOCALIZED_GENERAL_FORM_COLLECTION => $this->getLocalizedFormCollectionData(),
            ],
            CreateProductSetFormType::FIELD_SEO_FORM => [
                SeoFormType::FIELD_LOCALIZED_SEO_FORM_COLLECTION => $this->getLocalizedFormCollectionData(),
            ],
            CreateProductSetFormType::FIELD_IMAGES_FORM => $this->getImagesFormData(),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getLocalizedFormCollectionData()
    {
        $results = [];
        $localeCollection = $this->localeFacade->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $results[] = [
                LocalizedGeneralFormType::FIELD_FK_LOCALE => $localeTransfer->getIdLocale(),
            ];
        }

        return $results;
    }

    /**
     * @return array
     */
    protected function getImagesFormData()
    {
        $results = [];
        $results[ImagesFormType::getImageSetFormName()][] = $this->getImagesDefaultFields();

        $availableLocales = $this->localeFacade->getLocaleCollection();
        foreach ($availableLocales as $localeTransfer) {
            $results[ImagesFormType::getImageSetFormName($localeTransfer->getLocaleName())][] = $this->getImagesDefaultFields($localeTransfer);
        }

        return $results;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    protected function getImagesDefaultFields(LocaleTransfer $localeTransfer = null)
    {
        return [
            LocalizedProductImageSetFormType::FIELD_FK_LOCALE => ($localeTransfer ? $localeTransfer->getIdLocale() : null),
            LocalizedProductImageSetFormType::FIELD_PRODUCT_IMAGE_COLLECTION => [[]],
        ];
    }

}
