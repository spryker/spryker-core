<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Manager;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\Category\CategoryConfig;
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
     * @param CategoryTreeReaderInterface $categoryTreeReader
     * @param UrlPathGeneratorInterface $urlPathGenerator
     * @param CategoryToUrlInterface $urlFacade
     */
    public function __construct(
        CategoryTreeReaderInterface $categoryTreeReader,
        UrlPathGeneratorInterface $urlPathGenerator,
        CategoryToUrlInterface $urlFacade
    ) {
        $this->categoryTreeReader = $categoryTreeReader;
        $this->urlPathGenerator = $urlPathGenerator;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function createUrl(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $path = $this->categoryTreeReader->getPath($categoryNode->getIdCategoryNode(), $locale);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNode->getIdCategoryNode();

        $url = $this->urlFacade->createUrl($categoryUrl, $locale, CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idNode);
        $this->urlFacade->touchUrlActive($url->getIdUrl());
    }

}
