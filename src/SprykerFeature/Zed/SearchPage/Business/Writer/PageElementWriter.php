<?php

namespace SprykerFeature\Zed\SearchPage\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Shared\SearchPage\Dependency\PageElementInterface;
use SprykerFeature\Zed\SearchPage\Business\Reader\PageElementReaderInterface;
use SprykerFeature\Zed\SearchPage\Dependency\Facade\SearchPageToTouchInterface;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageElement;

class PageElementWriter implements PageElementWriterInterface
{
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
     * @return int
     * @throws PropelException
     */
    public function createPageElement(PageElementInterface $pageElement)
    {
        $pageElementEntity = new SpySearchPageElement();

        $pageElementEntity->fromArray($pageElement->toArray());
        $pageElementEntity->save();

        $this->touchFacade->touchActive('search-page-config', 1);

        return $pageElementEntity->getPrimaryKey();
    }

    /**
     * @param PageElementInterface $pageElement
     *
     * @return int
     * @throws PropelException
     */
    public function updatePageElement(PageElementInterface $pageElement)
    {
        $idElement = $pageElement->getIdSearchPageElement();
        $pageElementEntity = $this->elementReader->getPageElementById($idElement);

        $pageElementEntity->fromArray($pageElement->toArray());
        $pageElementEntity->save();

        return $idElement;
    }

    /**
     * @param PageElementInterface $pageElement
     *
     * @return int
     * @throws PropelException
     */
    public function deletePageElement(PageElementInterface $pageElement)
    {
        $idElement = $pageElement->getIdSearchPageElement();
        $pageElementEntity = $this->elementReader->getPageElementById($idElement);

        $pageElementEntity->delete();

        return $idElement;
    }

    /**
     * @param int $idPageElement
     * @param bool $isElementActive
     *
     * @return bool
     * @throws PropelException
     */
    public function switchActiveState($idPageElement, $isElementActive)
    {
        $pageElementEntity = $this->elementReader->getPageElementById($idPageElement);
        $pageElementEntity->setIsElementActive($isElementActive);
        $pageElementEntity->save();

        return true;
    }
}
