<?php

namespace SprykerFeature\SearchPage\Business\Reader;

use SprykerFeature\SearchPage\Persistence\SearchPageQueryContainer;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageAttributeTemplate;

class PageAttributeTemplateReader implements PageAttributeTemplateReaderInterface
{

    /**
     * @var SearchPageQueryContainer
     */
    private $searchPageQueryContainer;

    /**
     * @param SearchPageQueryContainer $searchPageQueryContainer
     */
    public function __construct(SearchPageQueryContainer $searchPageQueryContainer)
    {
        $this->searchPageQueryContainer = $searchPageQueryContainer;
    }

    /**
     * @param $idTemplate
     *
     * @return SpySearchPageAttributeTemplate
     */
    public function getTemplateById($idTemplate)
    {
        $templateQuery = $this->searchPageQueryContainer
            ->queryPageAttributeTemplateByPrimaryKey($idTemplate)
        ;

        return $templateQuery->findOne();
    }

    /**
     * @param $templateName
     *
     * @return bool
     */
    public function hasTemplateByName($templateName)
    {
        $documentAttributeQuery = $this->searchPageQueryContainer
            ->queryPageAttributeTemplateByName($templateName)
        ;

        return $documentAttributeQuery->count() > 0;
    }

    /**
     * @return bool
     */
    public function hasTemplates()
    {
        $documentAttributeQuery = $this->searchPageQueryContainer->queryPageAttributeTemplate();

        return $documentAttributeQuery->count() > 0;
    }
}
