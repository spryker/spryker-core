<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Cms\CmsDependencyProvider;
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
    const CMS_VERSION_COUNT = 'cmsVersionCount';
    const ALIAS_CMS_PAGE_LOCALIZED_ATTRIBUTE = 'aliasCmsPageLocalizedAttribute';
    const ALIAS_CMS_PAGE_TEMPLATE = 'aliasCmsPageTemplate';
    const ALIAS_CMS_GLOSSARY_KEY_MAPPING = 'aliasCmsGlossaryKeyMapping';
    const ALIAS_GLOSSARY_KEY = 'aliasGlossaryKey';
    const ALIAS_TRANSLATION = 'aliasTranslation';
    const ALIAS_LOCALE_FOR_LOCALIZED_ATTRIBUTE = 'aliasLocaleForLocalizedAttribute';
    const ALIAS_LOCALE_FOR_TRANSLATION = 'aliasLocaleForTranslation';

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
            ->withColumn(sprintf('GROUP_CONCAT(DISTINCT %s)', SpyUrlTableMap::COL_URL), static::CMS_URLS)
            ->innerJoinCmsTemplate()
            ->groupBy(SpyCmsPageTableMap::COL_ID_CMS_PAGE)
            ->groupBy(static::TEMPLATE_NAME)
            ->groupBy('name');
    }

    /**
     * @api
     *
     * @param int $idCmsPage
     * @param string $localName
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithUrlByIdCmsPageAndLocaleName($idCmsPage, $localName)
    {
        return $this->queryPages()
            ->filterByIdCmsPage($idCmsPage)
            ->addJoin(
                SpyCmsPageTableMap::COL_ID_CMS_PAGE,
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE,
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                SpyUrlTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::INNER_JOIN
            )
            ->addAnd(SpyLocaleTableMap::COL_LOCALE_NAME, $localName, Criteria::EQUAL)
            ->withColumn(SpyUrlTableMap::COL_URL, static::URL);
    }

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocaleAndVersion($idLocale)
    {
        return $this->queryPagesWithTemplatesForSelectedLocale($idLocale)
            ->addJoin(
                SpyCmsPageTableMap::COL_ID_CMS_PAGE,
                SpyCmsVersionTableMap::COL_FK_CMS_PAGE,
                Criteria::LEFT_JOIN
            )
            ->withColumn(sprintf('COUNT(DISTINCT %s)', SpyCmsVersionTableMap::COL_VERSION), static::CMS_VERSION_COUNT);
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
            ->withColumn("GROUP_CONCAT(" . SpyUrlTableMap::COL_URL . ")", self::URL)
            ->withColumn(self::IS_ACTIVE)
            ->groupByIdCmsPage();
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

    /**
     * @api
     *
     * @param int $idPage
     * @param int $idLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPageAndFkLocale($idPage, $idLocale)
    {
        return $this->queryCmsPageLocalizedAttributesByFkPage($idPage)
            ->filterByFkLocale($idLocale);
    }

    /**
     * @api
     *
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdPage(array $placeholders, $idCmsPage)
    {
        return $this->queryGlossaryKeyMappings()
            ->leftJoinGlossaryKey()
            ->filterByPlaceholder($placeholders, Criteria::IN)
            ->filterByFkPage($idCmsPage);
    }

    /**
     * @api
     *
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCmsPageWithAllRelationsByIdPage($idPage)
    {
        return $this->getFactory()->createCmsPageQuery()
            ->filterByIdCmsPage($idPage)
            ->innerJoinCmsTemplate(self::ALIAS_CMS_PAGE_TEMPLATE)
            ->useSpyCmsGlossaryKeyMappingQuery(self::ALIAS_CMS_GLOSSARY_KEY_MAPPING, Criteria::LEFT_JOIN)
                ->useGlossaryKeyQuery(self::ALIAS_GLOSSARY_KEY)
                    ->useSpyGlossaryTranslationQuery(self::ALIAS_TRANSLATION)
                        ->useLocaleQuery(self::ALIAS_LOCALE_FOR_TRANSLATION)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->useSpyCmsPageLocalizedAttributesQuery(self::ALIAS_CMS_PAGE_LOCALIZED_ATTRIBUTE)
                ->useLocaleQuery(self::ALIAS_LOCALE_FOR_LOCALIZED_ATTRIBUTE)
                ->endUse()
            ->endUse()
            ->with(self::ALIAS_CMS_PAGE_LOCALIZED_ATTRIBUTE)
            ->with(self::ALIAS_LOCALE_FOR_LOCALIZED_ATTRIBUTE)
            ->with(self::ALIAS_CMS_PAGE_TEMPLATE)
            ->with(self::ALIAS_CMS_GLOSSARY_KEY_MAPPING)
            ->with(self::ALIAS_GLOSSARY_KEY)
            ->with(self::ALIAS_TRANSLATION)
            ->with(self::ALIAS_LOCALE_FOR_TRANSLATION);
    }

    /**
     * @api
     *
     * @param int $idPage
     * @param string $versionOrder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionByIdPage($idPage, $versionOrder = Criteria::DESC)
    {
        return $this->getFactory()
            ->createSpyCmsVersionQuery()
            ->filterByFkCmsPage($idPage)
            ->orderBy(SpyCmsVersionTableMap::COL_VERSION, $versionOrder);
    }

    /**
     * @api
     *
     * @param int $idCmsVersion
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionById($idCmsVersion)
    {
        return $this->getFactory()
            ->createSpyCmsVersionQuery()
            ->filterByIdCmsVersion($idCmsVersion);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryAllCmsVersions()
    {
        return $this->getFactory()
            ->createSpyCmsVersionQuery();
    }

    /**
     * @api
     *
     * @param int $idPage
     * @param int $version
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionByIdPageAndVersion($idPage, $version)
    {
        return $this->queryCmsVersionByIdPage($idPage)
            ->filterByVersion($version);
    }

    /**
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByFkGlossaryKeys(array $idGlossaryKeys)
    {
        return $this->queryGlossaryKeyMappings()
            ->filterByFkGlossaryKey($idGlossaryKeys, Criteria::IN);
    }

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCmsPageId($idCmsPage)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByFkResourcePage($idCmsPage);

        return $query;
    }
}
