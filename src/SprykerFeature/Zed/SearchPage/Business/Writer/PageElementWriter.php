<?php

namespace SprykerFeature\Zed\SearchPage\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Shared\SearchPage\Dependency\PageElementInterface;
use SprykerFeature\Zed\SearchPage\Business\Reader\PageElementReaderInterface;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageElement;

class PageElementWriter implements PageElementWriterInterface
{
    /**
     * @var PageElementReaderInterface
     */
    private $elementReader;

    /**
     * @param PageElementReaderInterface $elementReader
     */
    public function __construct(PageElementReaderInterface $elementReader)
    {
        $this->elementReader = $elementReader;
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
