<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageFormType;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class CmsPageFormTypeDataProvider
{
    /**
     * @var \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface
     */
    protected $localFacade;

    /**
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface $cmsFacade
     * @param \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface $localFacade
     */
    public function __construct(
        CmsGuiToCmsQueryContainerInterface $cmsQueryContainer,
        CmsGuiToCmsInterface $cmsFacade,
        CmsGuiToLocaleInterface $localFacade
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsFacade = $cmsFacade;
        $this->localFacade = $localFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsPageTransfer::class,
            CmsPageAttributesFormType::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
            CmsPageFormType::OPTION_DATA_CLASS_ATTRIBUTES => CmsPageAttributesTransfer::class,
            CmsPageFormType::OPTION_DATA_CLASS_META_ATTRIBUTES => CmsPageMetaAttributesTransfer::class,
            CmsPageFormType::OPTION_TEMPLATE_CHOICES => $this->getTemplateList(),
        ];
    }

    /**
     * @param int|null $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer|null
     */
    public function getData($idCmsPage = null)
    {
        if (!$idCmsPage) {
            return $this->createInitialCmsPageTransfer();
        }

        return $this->cmsFacade->findCmsPageById($idCmsPage);
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        $templatesCollection = $this->cmsQueryContainer
            ->queryTemplates()
            ->find();

        $templatesList = [];
        foreach ($templatesCollection as $cmsTemplateEntity) {
            $templatesList[$cmsTemplateEntity->getIdCmsTemplate()] = $cmsTemplateEntity->getTemplateName();
        }

        return $templatesList;
    }

    /**
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function createInitialCmsPageTransfer()
    {
        $cmsPageTransfer = new CmsPageTransfer();

        foreach ($this->getAvailableLocales() as $localeTransfer) {
            $cmsPageAttributeTransfer = $this->createInitialCmsPageAttributesTransfer($localeTransfer);
            $cmsPageTransfer->addPageAttribute($cmsPageAttributeTransfer);

            $cmsPageMetaAttributeTransfer = $this->createInitialCmsPageMetaAttributesTransfer($localeTransfer);
            $cmsPageTransfer->addMetaAttribute($cmsPageMetaAttributeTransfer);
        }

        return $cmsPageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    protected function createInitialCmsPageAttributesTransfer(LocaleTransfer $localeTransfer)
    {
        $cmsPageAttributeTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributeTransfer->setLocaleName($localeTransfer->getLocaleName());
        $cmsPageAttributeTransfer->setFkLocale($localeTransfer->getIdLocale());
        $cmsPageAttributeTransfer->setUrlPrefix(
            $this->cmsFacade->getPageUrlPrefix($cmsPageAttributeTransfer)
        );

        return $cmsPageAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer
     */
    protected function createInitialCmsPageMetaAttributesTransfer(LocaleTransfer $localeTransfer)
    {
        $cmsPageMetaAttributeTransfer = new CmsPageMetaAttributesTransfer();
        $cmsPageMetaAttributeTransfer->setLocaleName($localeTransfer->getLocaleName());
        $cmsPageMetaAttributeTransfer->setFkLocale($localeTransfer->getIdLocale());

        return $cmsPageMetaAttributeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocales()
    {
        return $this->localFacade
            ->getLocaleCollection();
    }
}
