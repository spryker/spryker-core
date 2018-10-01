<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

/**
 * @method \Orm\Zed\Cms\Persistence\SpyCmsPageQuery queryPagesWithTemplates()
 */
interface CmsQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplates(): SpyCmsTemplateQuery;

    /**
     * @api
     *
     * @param string $path
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateByPath(string $path): SpyCmsTemplateQuery;

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateById(int $id): SpyCmsTemplateQuery;

    /**
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect(int $idUrl): SpyUrlQuery;

    /**
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById(int $idUrl): SpyUrlQuery;

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrlByIdPage(int $idCmsPage): SpyCmsPageQuery;

    /**
     * @api
     *
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingWithKeyById(int $idMapping): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPages(): SpyCmsPageQuery;

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageById(int $id): SpyCmsPageQuery;

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMapping(int $idPage, string $placeholder): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @api
     *
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById(int $idMapping): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappings(): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByPageId(int $idCmsPage): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocaleById(int $idLocale): SpyLocaleQuery;

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributes(): SpyCmsPageLocalizedAttributesQuery;

    /**
     * @api
     *
     * @param int $idCmsPage
     * @param int $fkLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsWithKeyByPageId(int $idCmsPage, int $fkLocale): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @api
     *
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPage(int $idPage): SpyCmsPageLocalizedAttributesQuery;

    /**
     * @api
     *
     * @param int $idPage
     * @param int $idLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPageAndFkLocale(int $idPage, int $idLocale): SpyCmsPageLocalizedAttributesQuery;

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey(string $key): SpyGlossaryKeyQuery;

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKey(string $key);

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocale(int $idLocale): ModelCriteria;

    /**
     * @api
     *
     * @param int $idCmsPage
     * @param string $localName
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithUrlByIdCmsPageAndLocaleName(int $idCmsPage, string $localName): SpyCmsPageQuery;

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocaleAndVersion(int $idLocale): ModelCriteria;

    /**
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationWithKeyByValue(string $value): SpyGlossaryTranslationQuery;

    /**
     * @api
     *
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdPage(array $placeholders, int $idCmsPage): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @api
     *
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryCmsPageWithAllRelationsByIdPage(int $idPage): SpyCmsPageQuery;

    /**
     * @api
     *
     * @param int $idPage
     * @param string $versionOrder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionByIdPage(int $idPage, string $versionOrder = Criteria::DESC): SpyCmsVersionQuery;

    /**
     * @api
     *
     * @param int $idCmsVersion
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionById(int $idCmsVersion): SpyCmsVersionQuery;

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryAllCmsVersions(): SpyCmsVersionQuery;

    /**
     * @api
     *
     * @param int $idPage
     * @param int $version
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionByIdPageAndVersion(int $idPage, int $version): SpyCmsVersionQuery;

    /**
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByFkGlossaryKeys(array $idGlossaryKeys): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCmsPageId(int $idCmsPage): SpyUrlQuery;

    /**
     * @api
     *
     * @param string $key
     * @param int $localeId
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKeyAndLocale(string $key, int $localeId);

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrls(): SpyCmsPageQuery;

    /**
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsWithRedirect(): SpyUrlQuery;
}
