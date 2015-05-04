<?php

namespace SprykerFeature\Zed\Category\Business\Manager;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerFeature\Shared\Category\CategoryResourceSettings;
use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;
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
     * @param CategoryNode $categoryNode
     * @param LocaleDto $locale
     */
    public function createUrl(CategoryNode $categoryNode, LocaleDto $locale)
    {
        $path = $this->categoryTreeReader->getPath($categoryNode->getIdCategoryNode(), $locale);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNode->getIdCategoryNode();

        $url = $this->urlFacade->createUrl($categoryUrl, $locale, CategoryResourceSettings::RESOURCE_TYPE_CATEGORY_NODE, $idNode);
        $this->urlFacade->touchUrlActive($url->getIdUrl());
    }
}
