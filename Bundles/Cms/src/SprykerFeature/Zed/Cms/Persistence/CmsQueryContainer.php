<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMappingQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKeyQuery;

class CmsQueryContainer extends AbstractQueryContainer implements CmsQueryContainerInterface
{

    const TEMPLATE_NAME = 'template_name';
    const TEMPLATE_PATH = 'template_path';
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
        $query
            ->filterByTemplatePath($path)
        ;

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
        $query
            ->filterByIdCmsTemplate($id)
        ;

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

    public function queryPageWithTemplatesAndUrls()
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate(null, Criteria::LEFT_JOIN)
            ->leftJoinSpyUrl(null,Criteria::LEFT_JOIN)
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
        $query
            ->filterByIdCmsPage($id)
        ;

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
        $query
            ->filterByFkPage($idPage)
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
        $query
            ->filterByIdCmsGlossaryKeyMapping($idMapping)
        ;

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
        $query
            ->filterByIdCmsGlossaryKeyMapping($idMapping)
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
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsWithKeyByPageId($idCmsPage,$fkLocale)
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
     * @param int $id
     *
     * @return SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($id)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::URL_QUERY_CONTAINER)->queryUrlByIdWithRedirect($id);
    }

    /**
     * @param int $id
     *
     * @return SpyUrlQuery
     */
    public function queryRedirectById($id)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::URL_QUERY_CONTAINER)->queryRedirectById($id);
    }

    /**
     *
     * @return SpyUrlQuery
     */
    public function queryUrlsWithRedirect()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::URL_QUERY_CONTAINER)->queryUrlsWithRedirect();
    }

    /**
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryKey($key)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::GLOSSARY_QUERY_CONTAINER)->queryKey($key);
    }



}
