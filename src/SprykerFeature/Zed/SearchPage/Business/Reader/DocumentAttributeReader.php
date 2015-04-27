<?php

namespace SprykerFeature\SearchPage\Business\Reader;

use SprykerFeature\SearchPage\Persistence\SearchPageQueryContainer;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchDocumentAttribute;

class DocumentAttributeReader implements DocumentAttributeReaderInterface
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
     * @param string $documentName
     * @param string $type
     *
     * @return bool
     */
    public function hasDocumentAttributeByNameAndType($documentName, $type)
    {
        $documentAttributeQuery = $this->searchPageQueryContainer
            ->queryDocumentAttributeByNameAndType($documentName, $type)
        ;

        return $documentAttributeQuery->count() > 0;
    }

    /**
     * @return bool
     */
    public function hasDocumentAttributes()
    {
        $documentAttributeQuery = $this->searchPageQueryContainer->queryDocumentAttribute();

        return $documentAttributeQuery->count() > 0;
    }

    /**
     * @param $idDocumentAttribute
     *
     * @return SpySearchDocumentAttribute
     */
    public function getDocumentAttributeById($idDocumentAttribute)
    {
        $documentAttributeQuery = $this->searchPageQueryContainer
            ->queryDocumentAttributeByPrimaryKey($idDocumentAttribute)
        ;

        return $documentAttributeQuery->findOne();
    }
}
