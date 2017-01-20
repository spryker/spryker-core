<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\CmsGui\Communication\Form\CmsPageAttributesFormType;
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
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     *
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(
        array $availableLocales,
        CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
    ) {
        $this->availableLocales = $availableLocales;
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsPageTransfer::class,
            CmsPageAttributesFormType::OPTION_TEMPLATE_CHOICES => $this->getTemplateList()
        ];
    }

    /**
     * @return CmsPageTransfer
     */
    public function getData()
    {
        $cmsPageTransfer = new CmsPageTransfer();

        foreach ($this->availableLocales as $localeTransfer) {
            $cmsPageAttributeTransfer = new CmsPageAttributesTransfer();
            $cmsPageAttributeTransfer->setLocaleName($localeTransfer->getLocaleName());
            $cmsPageTransfer->addPageAttribute($cmsPageAttributeTransfer);

            $cmsPageMetaAttributeTransfer = new CmsPageMetaAttributesTransfer();
            $cmsPageMetaAttributeTransfer->setLocaleName($localeTransfer->getLocaleName());
            $cmsPageTransfer->addMetaAttribute($cmsPageMetaAttributeTransfer);
        }

        return $cmsPageTransfer;
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
}
