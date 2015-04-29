<?php

namespace SprykerFeature\Zed\SearchPage\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\Base\SpySearchDocumentAttributeQuery;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\Base\SpySearchPageAttributeTemplateQuery;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageElementQuery;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageElementTemplateQuery;

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
     * @return SpySearchPageElementQuery
     */
    public function queryPageElementById($idPageElement)
    {
        return SpySearchPageElementQuery::create()
            ->filterByPrimaryKey($idPageElement)
        ;
    }
}
