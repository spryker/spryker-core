<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CmsQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplates();

    /**
     * @api
     *
     * @param string $path
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateByPath($path);

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateById($id);

    /**
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($idUrl);

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrlByIdPage($idCmsPage);

    /**
     * @api
     *
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingWithKeyById($idMapping);

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPages();

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageById($id);

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMapping($idPage, $placeholder);

    /**
     * @api
     *
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idMapping);

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappings();

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByPageId($idCmsPage);

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Locale\Persistence\Base\SpyLocaleQuery
     */
    public function queryLocaleById($idLocale);

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributes();

    /**
     * @api
     *
     * @param int $idCmsPage
     * @param int $fkLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsWithKeyByPageId($idCmsPage, $fkLocale);

    /**
     * @api
     *
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPage($idPage);

    /**
     * @api
     *
     * @param int $idPage
     * @param int $idLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPageAndFkLocale($idPage, $idLocale);

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey($key);

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKey($key);

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocale($idLocale);

    /**
     * @api
     *
     * @param int $idCmsPage
     * @param string $localName
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithUrlByIdCmsPageAndLocaleName($idCmsPage, $localName);

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocaleAndVersion($idLocale);

    /**
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationWithKeyByValue($value);

    /**
     * @api
     *
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdPage(array $placeholders, $idCmsPage);

    /**
     * @api
     *
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryCmsPageWithAllRelationsByIdPage($idPage);

    /**
     * @api
     *
     * @param int $idPage
     * @param string $versionOrder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionByIdPage($idPage, $versionOrder = Criteria::DESC);

    /**
     * @api
     *
     * @param int $idCmsVersion
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionById($idCmsVersion);

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryAllCmsVersions();

    /**
     * @api
     *
     * @param int $idPage
     * @param int $version
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryCmsVersionByIdPageAndVersion($idPage, $version);

    /**
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByFkGlossaryKeys(array $idGlossaryKeys);

    /**
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCmsPageId($idCmsPage);
}
