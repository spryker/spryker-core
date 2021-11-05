<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsPersistenceFactory getFactory()
 */
class CmsQueryContainer extends AbstractQueryContainer implements CmsQueryContainerInterface
{
    /**
     * @var string
     */
    public const TEMPLATE_NAME = 'template_name';

    /**
     * @var string
     */
    public const TEMPLATE_PATH = 'template_path';

    /**
     * @var string
     */
    public const CATEGORY_NODE_ID = 'categoryNodeId';

    /**
     * @var string
     */
    public const CATEGORY_NAME = 'categoryName';

    /**
     * @var string
     */
    public const ID_URL = 'id_url';

    /**
     * @var string
     */
    public const URL = 'url';

    /**
     * @var string
     */
    public const TO_URL = 'toUrl';

    /**
     * @var string
     */
    public const TRANS = 'trans';

    /**
     * @var string
     */
    public const KEY = 'keyname';

    /**
     * @var string
     */
    public const LABEL = 'label';

    /**
     * @var string
     */
    public const VALUE = 'value';

    /**
     * @var string
     */
    public const IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    public const CMS_URLS = 'cmsUrls';

    /**
     * @var string
     */
    public const CMS_VERSION_COUNT = 'cmsVersionCount';

    /**
     * @var string
     */
    public const ALIAS_CMS_PAGE_LOCALIZED_ATTRIBUTE = 'aliasCmsPageLocalizedAttribute';

    /**
     * @var string
     */
    public const ALIAS_CMS_PAGE_TEMPLATE = 'aliasCmsPageTemplate';

    /**
     * @var string
     */
    public const ALIAS_CMS_GLOSSARY_KEY_MAPPING = 'aliasCmsGlossaryKeyMapping';

    /**
     * @var string
     */
    public const ALIAS_GLOSSARY_KEY = 'aliasGlossaryKey';

    /**
     * @var string
     */
    public const ALIAS_TRANSLATION = 'aliasTranslation';

    /**
     * @var string
     */
    public const ALIAS_LOCALE_FOR_LOCALIZED_ATTRIBUTE = 'aliasLocaleForLocalizedAttribute';

    /**
     * @var string
     */
    public const ALIAS_LOCALE_FOR_TRANSLATION = 'aliasLocaleForTranslation';

    /**
     * @var string
     */
    public const ALIAS_CMS_PAGE_STORE_RELATION = 'aliasCmsPageStoreRelation';

    /**
     * @var string
     */
    public const ALIAS_STORE_FOR_STORE_RELATION = 'aliasStoreForStoreRelation';

    /**
     * @var string
     */
    public const CMS_NAME = 'name';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplates(): SpyCmsTemplateQuery
    {
        $query = $this->getFactory()->createCmsTemplateQuery();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $path
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateByPath(string $path): SpyCmsTemplateQuery
    {
        $query = $this->queryTemplates();
        $query->filterByTemplatePath($path);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateById(int $id): SpyCmsTemplateQuery
    {
        $query = $this->queryTemplates();
        $query->filterByIdCmsTemplate($id);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPages(): SpyCmsPageQuery
    {
        $query = $this->getFactory()->createCmsPageQuery();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPagesWithTemplates(): SpyCmsPageQuery
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate()
            ->withColumn(static::TEMPLATE_NAME)
            ->withColumn(static::TEMPLATE_PATH);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocale(int $idLocale): ModelCriteria
    {
        return $this->queryPages()
            ->useSpyCmsPageLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, static::TEMPLATE_NAME)
            ->withColumn(SpyCmsPageLocalizedAttributesTableMap::COL_NAME, static::CMS_NAME)
            ->addJoin(
                SpyCmsPageTableMap::COL_ID_CMS_PAGE,
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE,
                Criteria::LEFT_JOIN,
            )
            ->withColumn(sprintf('GROUP_CONCAT(DISTINCT %s)', SpyUrlTableMap::COL_URL), static::CMS_URLS)
            ->innerJoinCmsTemplate()
            ->groupBy(SpyCmsPageTableMap::COL_ID_CMS_PAGE)
            ->groupBy(static::TEMPLATE_NAME)
            ->groupBy(static::CMS_NAME);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsPage
     * @param string $localName
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithUrlByIdCmsPageAndLocaleName(int $idCmsPage, string $localName): SpyCmsPageQuery
    {
        return $this->queryPages()
            ->filterByIdCmsPage($idCmsPage)
            ->addJoin(
                SpyCmsPageTableMap::COL_ID_CMS_PAGE,
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE,
                Criteria::LEFT_JOIN,
            )
            ->addJoin(
                SpyUrlTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::INNER_JOIN,
            )
            ->addAnd(SpyLocaleTableMap::COL_LOCALE_NAME, $localName, Criteria::EQUAL)
            ->withColumn(SpyUrlTableMap::COL_URL, static::URL);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocaleAndVersion(int $idLocale): ModelCriteria
    {
        return $this->queryPagesWithTemplatesForSelectedLocale($idLocale)
            ->addJoin(
                SpyCmsPageTableMap::COL_ID_CMS_PAGE,
                SpyCmsVersionTableMap::COL_FK_CMS_PAGE,
                Criteria::LEFT_JOIN,
            )
            ->withColumn(sprintf('COUNT(DISTINCT %s)', SpyCmsVersionTableMap::COL_VERSION), static::CMS_VERSION_COUNT);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryLocalizedPagesWithTemplates(): SpyCmsPageQuery
    {
        return $this->queryPages()
            ->leftJoinSpyCmsPageLocalizedAttributes()
            ->withColumn(sprintf('GROUP_CONCAT(DISTINCT %s)', SpyCmsPageLocalizedAttributesTableMap::COL_NAME), static::CMS_NAME)
            ->addJoin(
                SpyCmsPageTableMap::COL_ID_CMS_PAGE,
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE,
                Criteria::LEFT_JOIN,
            )
            ->withColumn(sprintf('GROUP_CONCAT(DISTINCT %s)', SpyUrlTableMap::COL_URL), static::CMS_URLS)
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, static::TEMPLATE_NAME)
            ->leftJoinCmsTemplate()
            ->addJoin(
                SpyCmsPageTableMap::COL_ID_CMS_PAGE,
                SpyCmsVersionTableMap::COL_FK_CMS_PAGE,
                Criteria::LEFT_JOIN,
            )
            ->withColumn(sprintf('COUNT(DISTINCT %s)', SpyCmsVersionTableMap::COL_VERSION), static::CMS_VERSION_COUNT)
            ->groupByIdCmsPage();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrls(): SpyCmsPageQuery
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate()
            ->innerJoinSpyUrl()
            ->withColumn(static::TEMPLATE_NAME)
            ->withColumn('GROUP_CONCAT(' . SpyUrlTableMap::COL_URL . ')', static::URL)
            ->withColumn(static::IS_ACTIVE)
            ->groupByIdCmsPage();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageById(int $id): SpyCmsPageQuery
    {
        $query = $this->queryPages();
        $query->filterByIdCmsPage($id);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMapping(int $idPage, string $placeholder): SpyCmsGlossaryKeyMappingQuery
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByFkPage($idPage)
            ->filterByPlaceholder($placeholder);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById(int $idMapping): SpyCmsGlossaryKeyMappingQuery
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByIdCmsGlossaryKeyMapping($idMapping);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingWithKeyById(int $idMapping): SpyCmsGlossaryKeyMappingQuery
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByIdCmsGlossaryKeyMapping($idMapping)
            ->leftJoinGlossaryKey()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, static::KEY);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappings(): SpyCmsGlossaryKeyMappingQuery
    {
        $query = $this->getFactory()->createCmsGlossaryKeyMappingQuery();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByPageId(int $idCmsPage): SpyCmsGlossaryKeyMappingQuery
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByFkPage($idCmsPage);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsPage
     * @param int $fkLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsWithKeyByPageId(int $idCmsPage, int $fkLocale): SpyCmsGlossaryKeyMappingQuery
    {
        $query = $this->queryGlossaryKeyMappings()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, static::KEY)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, static::TRANS)
            ->filterByFkPage($idCmsPage)
            ->useGlossaryKeyQuery()
                ->useSpyGlossaryTranslationQuery()
                    ->filterByFkLocale($fkLocale)
                ->endUse()
            ->endUse();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect(int $idUrl): SpyUrlQuery
    {
        return $this->getUrlQueryContainer()
            ->queryUrlByIdWithRedirect($idUrl);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idUrlRedirect
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirectById(int $idUrlRedirect): SpyUrlRedirectQuery
    {
        return $this->getUrlQueryContainer()
            ->queryRedirectById($idUrlRedirect);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsWithRedirect(): SpyUrlQuery
    {
        return $this->getUrlQueryContainer()
            ->queryUrlsWithRedirect();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey(string $key): SpyGlossaryKeyQuery
    {
        return $this->getGlossaryQueryContainer()
            ->queryKey($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrlByIdPage(int $idCmsPage): SpyCmsPageQuery
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById(int $idUrl): SpyUrlQuery
    {
        return $this->getUrlQueryContainer()
            ->queryUrlById($idUrl);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationWithKeyByValue(string $value): SpyGlossaryTranslationQuery
    {
        return $this->getGlossaryQueryContainer()
            ->queryTranslationByValue($value)
            ->innerJoinGlossaryKey()
            ->filterByIsActive(true)
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, static::LABEL)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, static::VALUE);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     * @param int $localeId
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKeyAndLocale(string $key, int $localeId)
    {
        $query = $this->getGlossaryQueryContainer()
            ->queryByKey($key)
            ->useSpyGlossaryTranslationQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale($localeId)
            ->endUse()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, static::LABEL)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, static::VALUE);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKey(string $key)
    {
        $query = $this->getGlossaryQueryContainer()
            ->queryByKey($key)
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, static::LABEL);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $categoryName
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryName(string $categoryName, int $idLocale): SpyCategoryNodeQuery
    {
        return $this->getCategoryQueryContainer()
            ->queryCategoryNode($idLocale)
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->where('lower(' . SpyCategoryAttributeTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($categoryName) . '%')
                    ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::CATEGORY_NAME)
                ->endUse()
            ->endUse()
            ->useSpyUrlQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE, static::CATEGORY_NODE_ID)
            ->withColumn(SpyUrlTableMap::COL_URL, static::URL);
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected function getCategoryQueryContainer(): CategoryQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocaleById(int $idLocale): SpyLocaleQuery
    {
        return $this->getFactory()->getLocaleQuery()->queryLocales()->filterByIdLocale($idLocale);
    }

    /**
     * @return \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected function getUrlQueryContainer(): UrlQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_URL);
    }

    /**
     * @return \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected function getGlossaryQueryContainer(): GlossaryQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_GLOSSARY);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributes(): SpyCmsPageLocalizedAttributesQuery
    {
        return $this->getFactory()->createCmsPageLocalizedAttributesQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPage(int $idPage): SpyCmsPageLocalizedAttributesQuery
    {
        return $this->getFactory()
            ->createCmsPageLocalizedAttributesQuery()
            ->filterByFkCmsPage($idPage);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPage
     * @param int $idLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPageAndFkLocale(int $idPage, int $idLocale): SpyCmsPageLocalizedAttributesQuery
    {
        return $this->queryCmsPageLocalizedAttributesByFkPage($idPage)
            ->filterByFkLocale($idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdPage(array $placeholders, int $idCmsPage): SpyCmsGlossaryKeyMappingQuery
    {
        return $this->queryGlossaryKeyMappings()
            ->leftJoinGlossaryKey()
            ->filterByPlaceholder($placeholders, Criteria::IN)
            ->filterByFkPage($idCmsPage);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryCmsPageWithAllRelationsByIdPage(int $idPage): SpyCmsPageQuery
    {
        return $this->getFactory()->createCmsPageQuery()
            ->filterByIdCmsPage($idPage)
            ->innerJoinCmsTemplate(static::ALIAS_CMS_PAGE_TEMPLATE)
            ->useSpyCmsGlossaryKeyMappingQuery(static::ALIAS_CMS_GLOSSARY_KEY_MAPPING, Criteria::LEFT_JOIN)
                ->useGlossaryKeyQuery(static::ALIAS_GLOSSARY_KEY, Criteria::LEFT_JOIN)
                    ->useSpyGlossaryTranslationQuery(static::ALIAS_TRANSLATION, Criteria::LEFT_JOIN)
                        ->useLocaleQuery(static::ALIAS_LOCALE_FOR_TRANSLATION, Criteria::LEFT_JOIN)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->useSpyCmsPageLocalizedAttributesQuery(static::ALIAS_CMS_PAGE_LOCALIZED_ATTRIBUTE, Criteria::LEFT_JOIN)
                ->useLocaleQuery(static::ALIAS_LOCALE_FOR_LOCALIZED_ATTRIBUTE, Criteria::LEFT_JOIN)
                ->endUse()
            ->endUse()
            ->useSpyCmsPageStoreQuery(static::ALIAS_CMS_PAGE_STORE_RELATION, Criteria::LEFT_JOIN)
                ->useSpyStoreQuery(static::ALIAS_STORE_FOR_STORE_RELATION, Criteria::LEFT_JOIN)
                ->endUse()
            ->endUse()
            ->with(static::ALIAS_CMS_PAGE_STORE_RELATION)
            ->with(static::ALIAS_STORE_FOR_STORE_RELATION)
            ->with(static::ALIAS_CMS_PAGE_LOCALIZED_ATTRIBUTE)
            ->with(static::ALIAS_LOCALE_FOR_LOCALIZED_ATTRIBUTE)
            ->with(static::ALIAS_CMS_PAGE_TEMPLATE)
            ->with(static::ALIAS_CMS_GLOSSARY_KEY_MAPPING)
            ->with(static::ALIAS_GLOSSARY_KEY)
            ->with(static::ALIAS_TRANSLATION)
            ->with(static::ALIAS_LOCALE_FOR_TRANSLATION);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPage
     * @param string $versionOrder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionByIdPage(int $idPage, string $versionOrder = Criteria::DESC): SpyCmsVersionQuery
    {
        return $this->getFactory()
            ->createSpyCmsVersionQuery()
            ->filterByFkCmsPage($idPage)
            ->orderBy(SpyCmsVersionTableMap::COL_VERSION, $versionOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsVersion
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionById(int $idCmsVersion): SpyCmsVersionQuery
    {
        return $this->getFactory()
            ->createSpyCmsVersionQuery()
            ->filterByIdCmsVersion($idCmsVersion);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryAllCmsVersions(): SpyCmsVersionQuery
    {
        return $this->getFactory()
            ->createSpyCmsVersionQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPage
     * @param int $version
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionByIdPageAndVersion(int $idPage, int $version): SpyCmsVersionQuery
    {
        return $this->queryCmsVersionByIdPage($idPage)
            ->filterByVersion($version);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByFkGlossaryKeys(array $idGlossaryKeys): SpyCmsGlossaryKeyMappingQuery
    {
        return $this->queryGlossaryKeyMappings()
            ->filterByFkGlossaryKey($idGlossaryKeys, Criteria::IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCmsPageId(int $idCmsPage): SpyUrlQuery
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByFkResourcePage($idCmsPage);

        return $query;
    }
}
