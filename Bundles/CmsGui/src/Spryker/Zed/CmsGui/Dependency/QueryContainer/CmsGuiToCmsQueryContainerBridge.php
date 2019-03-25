<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Dependency\QueryContainer;

use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;

class CmsGuiToCmsQueryContainerBridge implements CmsGuiToCmsQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct($cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplates()
    {
        return $this->cmsQueryContainer->queryTemplates();
    }

    /**
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationWithKeyByValue($value)
    {
        return $this->cmsQueryContainer->queryTranslationWithKeyByValue($value);
    }

    /**
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKey($key)
    {
        return $this->cmsQueryContainer->queryKeyWithTranslationByKey($key);
    }

    /**
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocale($idLocale)
    {
        return $this->cmsQueryContainer->queryPagesWithTemplatesForSelectedLocale($idLocale);
    }

    /**
     * @param int $idCmsPage
     * @param string $localName
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithUrlByIdCmsPageAndLocaleName($idCmsPage, $localName)
    {
        return $this->cmsQueryContainer->queryPageWithUrlByIdCmsPageAndLocaleName($idCmsPage, $localName);
    }

    /**
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPage($idPage)
    {
        return $this->cmsQueryContainer->queryCmsPageLocalizedAttributesByFkPage($idPage);
    }

    /**
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocaleAndVersion($idLocale)
    {
        return $this->cmsQueryContainer->queryPagesWithTemplatesForSelectedLocaleAndVersion($idLocale);
    }

    /**
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdPage(array $placeholders, $idCmsPage)
    {
        return $this->cmsQueryContainer->queryGlossaryKeyMappingByPlaceholdersAndIdPage($placeholders, $idCmsPage);
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributes()
    {
        return $this->cmsQueryContainer->queryCmsPageLocalizedAttributes();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryLocalizedPagesWithTemplates(): SpyCmsPageQuery
    {
        return $this->cmsQueryContainer->queryLocalizedPagesWithTemplates();
    }
}
