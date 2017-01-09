<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Form\DataProvider;

use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageFormDataProvider
{

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    protected $cmsToLocaleInterface;

    /**
     * @var \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsPageLocalizedAttributesFormDataProvider
     */
    private $cmsPageLocalizedAttributesFormDataProvider;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface $cmsToLocaleInterface
     * @param \Spryker\Zed\Cms\Communication\Form\DataProvider\CmsPageLocalizedAttributesFormDataProvider $cmsPageLocalizedAttributesFormDataProvider
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsToLocaleInterface $cmsToLocaleInterface,
        CmsPageLocalizedAttributesFormDataProvider $cmsPageLocalizedAttributesFormDataProvider
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsToLocaleInterface = $cmsToLocaleInterface;
        $this->cmsPageLocalizedAttributesFormDataProvider = $cmsPageLocalizedAttributesFormDataProvider;
    }

    /**
     * @param int|null $idPage
     *
     * @return array
     */
    public function getData($idPage = null)
    {
        if ($idPage === null) {
            return [];
        }

        $pageUrlTemplate = $this
            ->cmsQueryContainer
            ->queryPageWithTemplatesAndUrlByIdPage($idPage)
            ->findOne();

        return [
            CmsPageForm::FIELD_ID_CMS_PAGE => $pageUrlTemplate->getIdCmsPage(),
            CmsPageForm::FIELD_FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
            CmsPageForm::FIELD_URL => $pageUrlTemplate->getVirtualColumn('url'),
            CmsPageForm::FIELD_FK_LOCALE => $pageUrlTemplate->getVirtualColumn('idLocale'),
            CmsPageForm::FIELD_CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
            CmsPageForm::FIELD_IS_ACTIVE => $pageUrlTemplate->getIsActive(),
            CmsPageForm::FIELD_ID_URL => $pageUrlTemplate->getVirtualColumn('idUrl'),
            CmsPageForm::FIELD_IS_SEARCHABLE => $pageUrlTemplate->getIsSearchable(),
            CmsPageForm::FIELD_LOCALIZED_ATTRIBUTES => $this->cmsPageLocalizedAttributesFormDataProvider
                ->getData($pageUrlTemplate->getIdCmsPage(), $pageUrlTemplate->getVirtualColumn('idLocale')),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CmsPageForm::OPTION_TEMPLATE_CHOICES => $this->getTemplateList(),
            CmsPageForm::OPTION_LOCALES_CHOICES => $this->getAvailableLocales(),
        ];
    }

    /**
     * @return array
     */
    protected function getAvailableLocales()
    {
        return $this->cmsToLocaleInterface->getAvailableLocales();
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        $templates = $this->cmsQueryContainer->queryTemplates()->find();

        $result = [];

        /** @var \Orm\Zed\Cms\Persistence\SpyCmsTemplate $template */
        foreach ($templates->getData() as $template) {
            $result[$template->getIdCmsTemplate()] = $template->getTemplateName();
        }

        return $result;
    }

}
