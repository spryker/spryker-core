<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Reader;

use SprykerFeature\Zed\SearchPage\Persistence\SearchPageQueryContainer;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageElementTemplate;

class TemplateReader implements TemplateReaderInterface
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
     * @param int $idTemplate
     *
     * @return SpySearchPageElementTemplate
     */
    public function getTemplateById($idTemplate)
    {
        $templateQuery = $this->searchPageQueryContainer
            ->queryPageElementTemplateByPrimaryKey($idTemplate)
        ;

        return $templateQuery->findOne();
    }

    /**
     * @param string $templateName
     *
     * @return bool
     */
    public function hasTemplateByName($templateName)
    {
        $documentAttributeQuery = $this->searchPageQueryContainer
            ->queryPageElementTemplateByName($templateName)
        ;

        return $documentAttributeQuery->count() > 0;
    }

    /**
     * @return bool
     */
    public function hasTemplates()
    {
        $documentAttributeQuery = $this->searchPageQueryContainer->queryPageElementTemplate();

        return $documentAttributeQuery->count() > 0;
    }

}
