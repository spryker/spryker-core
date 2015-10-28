<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Shared\SearchPage\Dependency\PageElementInterface;
use SprykerFeature\Zed\SearchPage\Business\Reader\PageElementReaderInterface;
use SprykerFeature\Zed\SearchPage\Dependency\Facade\SearchPageToTouchInterface;
use Orm\Zed\SearchPage\Persistence\SpySearchPageElement;

class PageElementWriter implements PageElementWriterInterface
{

    const RESOURCE_TYPE_SEARCH_PAGE_CONFIG = 'search_page_config';
    const SEARCH_PAGE_CONFIG_ITEM_ID = 1;

    /**
     * @var PageElementReaderInterface
     */
    private $elementReader;

    /**
     * @var SearchPageToTouchInterface
     */
    private $touchFacade;

    /**
     * @param PageElementReaderInterface $elementReader
     * @param SearchPageToTouchInterface $touchFacade
     */
    public function __construct(
        PageElementReaderInterface $elementReader,
        SearchPageToTouchInterface $touchFacade
    ) {
        $this->elementReader = $elementReader;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param PageElementInterface $pageElement
     *
     * @throws PropelException
     *
     * @return int
     */
    public function createPageElement(PageElementInterface $pageElement)
    {
        $pageElementEntity = new SpySearchPageElement();

        $pageElementEntity->fromArray($pageElement->toArray());
        $pageElementEntity->save();

        $idPageElement = $pageElementEntity->getPrimaryKey();

        $this->touchSearchPageConfig();

        return $idPageElement;
    }

    /**
     * @param PageElementInterface $pageElement
     *
     * @throws PropelException
     *
     * @return int
     */
    public function updatePageElement(PageElementInterface $pageElement)
    {
        $idPageElement = $pageElement->getIdSearchPageElement();
        $pageElementEntity = $this->elementReader->getPageElementById($idPageElement);

        $pageElementEntity->fromArray($pageElement->toArray());
        $pageElementEntity->save();

        $this->touchSearchPageConfig();

        return $idPageElement;
    }

    /**
     * @param PageElementInterface $pageElement
     *
     * @throws PropelException
     *
     * @return int
     */
    public function deletePageElement(PageElementInterface $pageElement)
    {
        $idPageElement = $pageElement->getIdSearchPageElement();
        $pageElementEntity = $this->elementReader->getPageElementById($idPageElement);

        $pageElementEntity->delete();

        $this->touchSearchPageConfig();

        return $idPageElement;
    }

    /**
     * @param int $idPageElement
     * @param bool $isElementActive
     *
     * @throws PropelException
     *
     * @return bool
     */
    public function switchActiveState($idPageElement, $isElementActive)
    {
        $pageElementEntity = $this->elementReader->getPageElementById($idPageElement);
        $pageElementEntity->setIsElementActive($isElementActive);
        $pageElementEntity->save();

        $this->touchSearchPageConfig();

        return true;
    }

    protected function touchSearchPageConfig()
    {
        $this->touchFacade->touchActive(self::RESOURCE_TYPE_SEARCH_PAGE_CONFIG, self::SEARCH_PAGE_CONFIG_ITEM_ID);
    }

}
