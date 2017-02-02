<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\Form\CmsBlockForm;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsPersistenceFactory getFactory()
 */
class CmsQueryContainer extends AbstractQueryContainer implements CmsQueryContainerInterface
{

    const TEMPLATE_NAME = 'template_name';
    const TEMPLATE_PATH = 'template_path';
    const CATEGORY_NODE_ID = 'categoryNodeId';
    const CATEGORY_NAME = 'categoryName';
    const ID_URL = 'id_url';
    const URL = 'url';
    const TO_URL = 'toUrl';
    const TRANS = 'trans';
    const KEY = 'keyname';
    const LABEL = 'label';
    const VALUE = 'value';
    const IS_ACTIVE = 'is_active';
    const CMS_URLS = 'cmsUrls';

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplates()
    {
        $query = $this->getFactory()->createCmsTemplateQuery();

        return $query;
    }

    /**
     * @api
     *
     * @param string $path
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateByPath($path)
    {
        $query = $this->queryTemplates();
        $query->filterByTemplatePath($path);

        return $query;
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateById($id)
    {
        $query = $this->queryTemplates();
        $query->filterByIdCmsTemplate($id);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPages()
    {
        $query = $this->getFactory()->createCmsPageQuery();

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlocks()
    {
        $query = $this->getFactory()->createCmsBlockQuery();

        return $query;
    }

    /**
     * @api
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplates()
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate()
            ->withColumn(self::TEMPLATE_NAME)
            ->withColumn(self::TEMPLATE_PATH);
    }

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocale($idLocale)
    {
        return $this->queryPages()
            ->useSpyCmsPageLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, static::TEMPLATE_NAME)
            ->withColumn(SpyCmsPageLocalizedAttributesTableMap::COL_NAME, 'name')
            ->addJoin(
                SpyCmsPageTableMap::COL_ID_CMS_PAGE,
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE,
                Criteria::LEFT_JOIN
            )
            ->withColumn("GROUP_CONCAT(" . SpyUrlTableMap::COL_URL . ")", self::CMS_URLS)
            ->innerJoinCmsTemplate()
            ->groupBy(SpyCmsPageTableMap::COL_ID_CMS_PAGE)
            ->groupBy(static::TEMPLATE_NAME)
            ->groupBy('name');
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrls()
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate()
            ->innerJoinSpyUrl()
            ->withColumn(self::TEMPLATE_NAME)
            ->withColumn(self::URL)
            ->withColumn(self::IS_ACTIVE);
    }

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryPageWithTemplatesAndBlocks($idLocale)
    {
        return $this->queryBlocks()
            ->leftJoinSpyCmsPage()
            ->useSpyCmsPageQuery()
                ->joinCmsTemplate()
                    ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, self::TEMPLATE_NAME)
                ->endUse()
            ->addJoin(
                SpyCmsBlockTableMap::COL_VALUE,
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                [SpyCategoryNodeTableMap::COL_FK_CATEGORY, SpyCategoryAttributeTableMap::COL_FK_LOCALE],
                [SpyCategoryAttributeTableMap::COL_FK_CATEGORY, $idLocale],
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                [SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, SpyUrlTableMap::COL_FK_LOCALE],
                [SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE, $idLocale],
                Criteria::LEFT_JOIN
            )
            ->withColumn(SpyUrlTableMap::COL_URL, self::URL)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::CATEGORY_NAME)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME)
            ->withColumn(self::IS_ACTIVE);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryPageWithTemplatesAndBlocksById($idCmsBlock)
    {
        return $this->queryBlocks()
            ->filterByIdCmsBlock($idCmsBlock)
            ->leftJoinSpyCmsPage()
            ->useSpyCmsPageQuery()
                ->joinCmsTemplate()
                    ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, self::TEMPLATE_NAME)
                ->endUse()
            ->addJoin(
                SpyCmsBlockTableMap::COL_VALUE,
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                Criteria::LEFT_JOIN
            )
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::CATEGORY_NAME)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME)
            ->withColumn(SpyCmsPageTableMap::COL_FK_TEMPLATE, CmsBlockForm::FIELD_FK_TEMPLATE)
            ->withColumn(SpyCmsPageTableMap::COL_IS_ACTIVE, 'isActive');
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageById($id)
    {
        $query = $this->queryPages();
        $query->filterByIdCmsPage($id);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMapping($idPage, $placeholder)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByFkPage($idPage)
            ->filterByPlaceholder($placeholder);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idMapping)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByIdCmsGlossaryKeyMapping($idMapping);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingWithKeyById($idMapping)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByIdCmsGlossaryKeyMapping($idMapping)
            ->leftJoinGlossaryKey()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::KEY);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappings()
    {
        $query = $this->getFactory()->createCmsGlossaryKeyMappingQuery();

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByPageId($idCmsPage)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByFkPage($idCmsPage);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCmsPage
     * @param int $fkLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsWithKeyByPageId($idCmsPage, $fkLocale)
    {
        $query = $this->queryGlossaryKeyMappings()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::KEY)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, self::TRANS)
            ->filterByFkPage($idCmsPage)
            ->useGlossaryKeyQuery()
                ->useSpyGlossaryTranslationQuery()
                    ->filterByFkLocale($fkLocale)
                ->endUse()
            ->endUse();

        return $query;
    }

    /**
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($idUrl)
    {
        return $this->getUrlQueryContainer()
            ->queryUrlByIdWithRedirect($idUrl);
    }

    /**
     * @api
     *
     * @param int $idUrlRedirect
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirectById($idUrlRedirect)
    {
        return $this->getUrlQueryContainer()
            ->queryRedirectById($idUrlRedirect);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsWithRedirect()
    {
        return $this->getUrlQueryContainer()
            ->queryUrlsWithRedirect();
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey($key)
    {
        return $this->getGlossaryQueryContainer()
            ->queryKey($key);
    }

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrlByIdPage($idCmsPage)
    {
        return $this->queryPages()
            ->filterByIdCmsPage($idCmsPage)
            ->leftJoinCmsTemplate()
            ->useSpyUrlQuery()
                ->leftJoinSpyLocale()
            ->endUse()
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, static::TEMPLATE_NAME)
            ->withColumn(SpyUrlTableMap::COL_URL, static::URL)
            ->withColumn(SpyUrlTableMap::COL_ID_URL, 'idUrl')
            ->withColumn(SpyLocaleTableMap::COL_ID_LOCALE, 'idLocale')
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_PATH, static::TEMPLATE_PATH);
    }

    /**
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById($idUrl)
    {
        return $this->getUrlQueryContainer()
            ->queryUrlById($idUrl);
    }

    /**
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationWithKeyByValue($value)
    {
        return $this->getGlossaryQueryContainer()
            ->queryTranslationByValue($value)
            ->innerJoinGlossaryKey()
            ->filterByIsActive(true)
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::LABEL)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, self::VALUE);
    }

    /**
     * @api
     *
     * @param string $key
     * @param int $localeId
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKeyAndLocale($key, $localeId)
    {
        $query = $this->getGlossaryQueryContainer()
            ->queryByKey($key)
            ->useSpyGlossaryTranslationQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale($localeId)
            ->endUse()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::LABEL)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, self::VALUE);

        return $query;
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKey($key)
    {
        $query = $this->getGlossaryQueryContainer()
            ->queryByKey($key)
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::LABEL);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockByIdPage($idCmsPage)
    {
        return $this->queryBlocks()
            ->filterByFkPage($idCmsPage);
    }

    /**
     * @api
     *
     * @param string $blockName
     * @param string $blockType
     * @param string $blockValue
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockByNameAndTypeValue($blockName, $blockType, $blockValue)
    {
        return $this->queryBlocks()
            ->filterByName($blockName)
            ->filterByType($blockType)
            ->filterByValue($blockValue);
    }

    /**
     * @api
     *
     * @param string $categoryName
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\Base\SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryName($categoryName, $idLocale)
    {
        return $this->getCategoryQueryContainer()
            ->queryCategoryNode($idLocale)
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->where('lower(' . SpyCategoryAttributeTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($categoryName) . '%')
                    ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::CATEGORY_NAME)
                ->endUse()
            ->endUse()
            ->useSpyUrlQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE, self::CATEGORY_NODE_ID)
            ->withColumn(SpyUrlTableMap::COL_URL, self::URL);
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockByIdCategoryNode($idCategoryNode)
    {
        return $this->queryBlocks()
            ->filterByType(CmsConstants::RESOURCE_TYPE_CATEGORY_NODE)
            ->filterByValue($idCategoryNode);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockById($idCmsBlock)
    {
        return $this
            ->queryBlocks()
            ->filterByIdCmsBlock($idCmsBlock);
    }

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocaleById($idLocale)
    {
        return $this->getFactory()->createLocaleQuery()->queryLocales()->filterByIdLocale($idLocale);
    }

    /**
     * @return \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected function getUrlQueryContainer()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_URL);
    }

    /**
     * @return \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected function getGlossaryQueryContainer()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_GLOSSARY);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributes()
    {
        return $this->getFactory()->createCmsPageLocalizedAttributesQuery();
    }

    /**
     * @api
     *
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPage($idPage)
    {
        return $this->getFactory()
            ->createCmsPageLocalizedAttributesQuery()
            ->filterByFkCmsPage($idPage);
    }

}
