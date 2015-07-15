<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMappingQuery;

class CmsQueryContainer extends AbstractQueryContainer implements CmsQueryContainerInterface
{

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
            ->withColumn('template_name')
            ->withColumn('template_path')
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

}
