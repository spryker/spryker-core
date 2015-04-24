<?php

namespace SprykerFeature\Zed\Category\Business\Manager;

use SprykerFeature\Shared\Category\CategoryResourceSettings;
use SprykerFeature\Shared\Category\Transfer\CategoryNode;
use SprykerFeature\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeReaderInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToUrlInterface;

class NodeUrlManager implements NodeUrlManagerInterface
{
    /**
     * @var CategoryTreeReaderInterface
     */
    protected $categoryTreeReader;

    /**
     * @var UrlPathGeneratorInterface
     */
    protected $urlPathGenerator;

    /**
     * @var CategoryToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param CategoryTreeReaderInterface $categoryTreeReader
     * @param UrlPathGeneratorInterface $urlPathGenerator
     * @param CategoryToUrlInterface $urlFacade
     * @param string $localeName
     */
    public function __construct(
        CategoryTreeReaderInterface $categoryTreeReader,
        UrlPathGeneratorInterface $urlPathGenerator,
        CategoryToUrlInterface $urlFacade,
        $localeName
    ) {
        $this->categoryTreeReader = $categoryTreeReader;
        $this->urlPathGenerator = $urlPathGenerator;
        $this->urlFacade = $urlFacade;
        $this->localeName = $localeName;
    }

    /**
     * @param CategoryNode $categoryNode
     * @param int $idLocale
     */
    public function createUrl(CategoryNode $categoryNode, $idLocale)
    {
        $path = $this->categoryTreeReader->getPath($categoryNode->getIdCategoryNode(), $idLocale);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNode->getIdCategoryNode();

        $this->urlFacade->createUrl($categoryUrl, $this->localeName, CategoryResourceSettings::RESOURCE_TYPE_CATEGORY_NODE, $idNode);
        $this->urlFacade->touchUrlActive($idNode);
    }
}
