<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\SearchPage\Persistence\Base\SpySearchDocumentAttributeQuery;
use Orm\Zed\SearchPage\Persistence\Map\SpySearchDocumentAttributeTableMap;
use Orm\Zed\SearchPage\Persistence\Map\SpySearchPageElementTemplateTableMap;
use Orm\Zed\SearchPage\Persistence\SpySearchPageElementQuery;
use Orm\Zed\SearchPage\Persistence\SpySearchPageElementTemplateQuery;

/**
 * @method SearchPageDependencyContainer getDependencyContainer()
 */
class SearchPageQueryContainer extends AbstractQueryContainer
{

    /**
     * @return SpySearchDocumentAttributeQuery
     */
    public function queryDocumentAttribute()
    {
        return SpySearchDocumentAttributeQuery::create();
    }

    /**
     * @return SpySearchDocumentAttributeQuery
     */
    public function queryDocumentAttributeNames()
    {
        return SpySearchDocumentAttributeQuery::create()
            ->withColumn(SpySearchDocumentAttributeTableMap::COL_ID_SEARCH_DOCUMENT_ATTRIBUTE, 'id')
            ->withColumn(SpySearchDocumentAttributeTableMap::COL_ATTRIBUTE_NAME, 'name')
        ;
    }

    /**
     * @param int $idDocumentAttribute
     *
     * @return SpySearchDocumentAttributeQuery
     */
    public function queryDocumentAttributeByPrimaryKey($idDocumentAttribute)
    {
        return SpySearchDocumentAttributeQuery::create()
            ->filterByIdSearchDocumentAttribute($idDocumentAttribute)
        ;
    }

    /**
     * @param string $name
     * @param string $type
     *
     * @return SpySearchDocumentAttributeQuery
     */
    public function queryDocumentAttributeByNameAndType($name, $type)
    {
        return SpySearchDocumentAttributeQuery::create()
            ->filterByAttributeName($name)
            ->filterByAttributeType($type)
        ;
    }

    /**
     * @return SpySearchPageElementTemplateQuery
     */
    public function queryPageElementTemplate()
    {
        return SpySearchPageElementTemplateQuery::create();
    }

    /**
     * @return SpySearchPageElementTemplateQuery
     */
    public function queryPageElementTemplateNames()
    {
        return SpySearchPageElementTemplateQuery::create()
            ->withColumn(SpySearchPageElementTemplateTableMap::COL_ID_SEARCH_PAGE_ELEMENT_TEMPLATE, 'id')
            ->withColumn(SpySearchPageElementTemplateTableMap::COL_TEMPLATE_NAME, 'name')
        ;
    }

    /**
     * @param int $idTemplate
     *
     * @return SpySearchPageElementTemplateQuery
     */
    public function queryPageElementTemplateByPrimaryKey($idTemplate)
    {
        return SpySearchPageElementTemplateQuery::create()
            ->filterByIdSearchPageElementTemplate($idTemplate)
        ;
    }

    /**
     * @param string $name
     *
     * @return SpySearchPageElementTemplateQuery
     */
    public function queryPageElementTemplateByName($name)
    {
        return SpySearchPageElementTemplateQuery::create()
            ->filterByTemplateName($name)
        ;
    }

    /**
     * @param int $idPageElement
     *
     * @return SpySearchPageElementQuery
     */
    public function queryPageElementById($idPageElement)
    {
        return SpySearchPageElementQuery::create()
            ->filterByPrimaryKey($idPageElement)
        ;
    }

    /**
     * @return ModelCriteria
     */
    public function queryPageElementGrid()
    {
        return SpySearchPageElementQuery::create()
            ->joinElementTemplate()
            ->withColumn(SpySearchPageElementTemplateTableMap::COL_TEMPLATE_NAME, 'template_name')
            ->joinDocumentAttribute()
            ->withColumn(SpySearchDocumentAttributeTableMap::COL_ATTRIBUTE_NAME, 'attribute_name')
            ->withColumn(SpySearchDocumentAttributeTableMap::COL_ATTRIBUTE_TYPE, 'attribute_type')
        ;
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandSearchPageConfigQuery(ModelCriteria $expandableQuery)
    {
        return $this->getDependencyContainer()
            ->createSearchPageConfigQueryExpander()
            ->expandQuery($expandableQuery)
        ;
    }

}
