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
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class CmsPageFormTypeDataProvider
{

    /**
     * @var array|\Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected $availableLocales;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected $cmsPageTransfer;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface $cmsFacade
     * @param \Generated\Shared\Transfer\CmsPageTransfer|null $cmsPageTransfer
     */
    public function __construct(
        array $availableLocales,
        CmsGuiToCmsQueryContainerInterface $cmsQueryContainer,
        CmsGuiToCmsInterface $cmsFacade,
        CmsPageTransfer $cmsPageTransfer = null
    ) {
        $this->availableLocales = $availableLocales;
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsPageTransfer = $cmsPageTransfer;
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsPageTransfer::class,
            CmsPageAttributesFormType::OPTION_AVAILABLE_LOCALES => $this->availableLocales,
            CmsPageFormType::OPTION_DATA_CLASS_ATTRIBUTES => CmsPageAttributesTransfer::class,
            CmsPageFormType::OPTION_DATA_CLASS_META_ATTRIBUTES => CmsPageMetaAttributesTransfer::class,
            CmsPageFormType::OPTION_TEMPLATE_CHOICES => $this->getTemplateList(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function getData()
    {
        if (!$this->cmsPageTransfer) {
            $this->cmsPageTransfer = $this->createInitialCmsPageTransfer();
        }

        return $this->cmsPageTransfer;
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
        foreach ($this->availableLocales as $localeTransfer) {

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
        $cmsPageAttributeTransfer->setUrlPrefix($this->cmsFacade->getPageUrlPrefix($localeTransfer->getLocaleName()));

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

}
