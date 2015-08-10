<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Communication\Form\CmsPageForm;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsPageTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsTemplateTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMappingQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKeyQuery;
use SprykerFeature\Zed\Url\Persistence\Propel\Map\SpyUrlTableMap;

class CmsQueryContainer extends AbstractQueryContainer implements CmsQueryContainerInterface
{

    const TEMPLATE_NAME = 'template_name';
    const TEMPLATE_PATH = 'template_path';
    const ID_URL = 'id_url';
    const URL = 'url';
    const TO_URL = 'toUrl';
    const TRANS = 'trans';
    const KEY = 'keyname';

    /**
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplates()
    {
        $query = SpyCmsTemplateQuery::create();

        return $query;
    }

    /**
     * @param string $path
     *
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplateByPath($path)
    {
        $query = $this->queryTemplates();
        $query->filterByTemplatePath($path);

        return $query;
    }

    /**
     * @param int $id
     *
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplateById($id)
    {
        $query = $this->queryTemplates();
        $query->filterByIdCmsTemplate($id);

        return $query;
    }

    /**
     * @return SpyCmsPageQuery
     */
    public function queryPages()
    {
        $query = SpyCmsPageQuery::create();

        return $query;
    }

    /**
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplates()
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate(null, Criteria::LEFT_JOIN)
            ->withColumn(self::TEMPLATE_NAME)
            ->withColumn(self::TEMPLATE_PATH)
            ;
    }

    /**
     * @return SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrls()
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate(null, Criteria::LEFT_JOIN)
            ->leftJoinSpyUrl(null, Criteria::LEFT_JOIN)
            ->withColumn(self::TEMPLATE_NAME)
            ->withColumn(self::URL)
            ;
    }

    /**
     * @param int $id
     *
     * @return SpyCmsPageQuery
     */
    public function queryPageById($id)
    {
        $query = $this->queryPages();
        $query->filterByIdCmsPage($id);

        return $query;
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMapping($idPage, $placeholder)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByFkPage($idPage)
            ->filterByPlaceholder($placeholder)
        ;

        return $query;
    }

    /**
     * @param int $idMapping
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idMapping)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByIdCmsGlossaryKeyMapping($idMapping);

        return $query;
    }

    /**
     * @param int $idMapping
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingWithKeyById($idMapping)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByIdCmsGlossaryKeyMapping($idMapping)
            ->leftJoinGlossaryKey()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::KEY)
        ;

        return $query;
    }

    /**
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappings()
    {
        $query = SpyCmsGlossaryKeyMappingQuery::create();

        return $query;
    }

    /**
     * @param int $idCmsPage
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByPageId($idCmsPage)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByFkPage($idCmsPage);

        return $query;
    }

    /**
     * @param int $idCmsPage
     * @param int $fkLocale
     *
     * @return SpyCmsGlossaryKeyMappingQuery
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
                ->endUse()
        ;

        return $query;
    }

    /**
     * @param int $idUrl
     *
     * @return SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($idUrl)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::URL_QUERY_CONTAINER)
            ->queryUrlByIdWithRedirect($idUrl)
            ;
    }

    /**
     * @param int $idRedirect
     *
     * @return SpyUrlQuery
     */
    public function queryRedirectById($idRedirect)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::URL_QUERY_CONTAINER)
            ->queryRedirectById($idRedirect)
            ;
    }

    /**
     * @return SpyUrlQuery
     */
    public function queryUrlsWithRedirect()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::URL_QUERY_CONTAINER)
            ->queryUrlsWithRedirect()
            ;
    }

    /**
     * @param string $key
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryKey($key)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::GLOSSARY_QUERY_CONTAINER)
            ->queryKey($key)
            ;
    }

    /**
     * @param int $idCmsPage
     *
     * @return SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrlByIdPage($idCmsPage)
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate()
            ->leftJoinSpyUrl()
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, self::TEMPLATE_NAME)
            ->withColumn(SpyUrlTableMap::COL_URL, self::URL)
            ->withColumn(SpyUrlTableMap::COL_ID_URL, 'idUrl')
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_PATH, self::TEMPLATE_PATH)
            ->withColumn(CmsPageForm::IS_ACTIVE)
            ->filterByIdCmsPage($idCmsPage)
            ;
    }

    /**
     * @param int $idUrl
     *
     * @return SpyUrlQuery
     */
    public function queryUrlById($idUrl)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::URL_QUERY_CONTAINER)
            ->queryUrlById($idUrl)
            ;
    }

}
