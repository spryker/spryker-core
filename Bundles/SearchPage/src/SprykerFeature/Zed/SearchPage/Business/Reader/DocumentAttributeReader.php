<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Reader;

use SprykerFeature\Zed\SearchPage\Persistence\SearchPageQueryContainer;
use Orm\Zed\SearchPage\Persistence\SpySearchDocumentAttribute;

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
     * @param string $name
     * @param string $type
     *
     * @return bool
     */
    public function hasDocumentAttributeByNameAndType($name, $type)
    {
        $documentAttributeQuery = $this->searchPageQueryContainer
            ->queryDocumentAttributeByNameAndType($name, $type)
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
     * @param int $idDocumentAttribute
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
