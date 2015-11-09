<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Reader;

use SprykerFeature\Zed\SearchPage\Business\Exception\PageElementDoesNotExistException;
use Orm\Zed\SearchPage\Persistence\SpySearchPageElement;
use SprykerFeature\Zed\SearchPage\Persistence\SearchPageQueryContainer;

class PageElementReader implements PageElementReaderInterface
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
     * @param int $idPageElement
     *
     * @throws PageElementDoesNotExistException
     *
     * @return SpySearchPageElement
     */
    public function getPageElementById($idPageElement)
    {
        $elementQuery = $this->searchPageQueryContainer->queryPageElementById($idPageElement);
        $pageElementEntity = $elementQuery->findOne();

        if ($pageElementEntity === null) {
            throw new PageElementDoesNotExistException(
                sprintf(
                    'PageElement %s does not exist in the DB',
                    $idPageElement
                )
            );
        }

        return $pageElementEntity;
    }

}
