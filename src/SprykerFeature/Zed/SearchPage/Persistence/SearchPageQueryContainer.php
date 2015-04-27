<?php

namespace SprykerFeature\SearchPage\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\Base\SpySearchDocumentAttributeQuery;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\Base\SpySearchPageAttributeTemplateQuery;

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
     * @param string $attributeName
     * @param string $type
     *
     * @return SpySearchDocumentAttributeQuery
     */
    public function queryDocumentAttributeByNameAndType($attributeName, $type)
    {
        return SpySearchDocumentAttributeQuery::create()
            ->filterByAttributeName($attributeName)
            ->filterByDocumentType($type)
        ;
    }

    /**
     * @return SpySearchPageAttributeTemplateQuery
     */
    public function queryPageAttributeTemplate()
    {
        return SpySearchPageAttributeTemplateQuery::create();
    }

    /**
     * @param int $idTemplate
     *
     * @return SpySearchPageAttributeTemplateQuery
     */
    public function queryPageAttributeTemplateByPrimaryKey($idTemplate)
    {
        return SpySearchPageAttributeTemplateQuery::create()
            ->filterByIdSearchPageAttributeTemplate($idTemplate)
            ;
    }

    /**
     * @param string $templateName
     *
     * @return SpySearchPageAttributeTemplateQuery
     */
    public function queryPageAttributeTemplateByName($templateName)
    {
        return SpySearchPageAttributeTemplateQuery::create()
            ->filterByTypeName($templateName)
            ;
    }
}
