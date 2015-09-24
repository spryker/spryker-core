<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Manager;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use SprykerFeature\Shared\Category\CategoryConfig;
use SprykerFeature\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeReaderInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTable;

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

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function updateUrl(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $path = $this->categoryTreeReader->getPath($categoryNode->getIdCategoryNode(), $locale);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNode->getIdCategoryNode();

        $url = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($idNode, $locale);
        
        if (!$url) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setFkPage(null);
            $urlTransfer->setResourceId($idNode);
            $urlTransfer->setResourceType(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE);
            $urlTransfer->setUrl($categoryUrl);
            $urlTransfer->setFkLocale($locale->getIdLocale());

            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }
        else {
            $url->setUrl($categoryUrl);
            $this->urlFacade->saveUrlAndTouch($url);
        }

        $this->updateChildrenUrls($categoryNode, $locale);
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    protected function updateChildrenUrls(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $children = $this->categoryTreeReader->getPathChildren($categoryNode->getIdCategoryNode());
        foreach ($children as $child) {
            /**
             * @var SpyCategoryClosureTable $child
             * @var SpyCategoryClosureTable $parent
             */
            $urlTransfer = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($child->getFkCategoryNodeDescendant(), $locale);
            if (!$urlTransfer) {
                continue;
            }

            $parentList = $this->categoryTreeReader->getPathParents($child->getFkCategoryNodeDescendant());
            $pathTokens = [];
            foreach ($parentList as $parent) {
                $pathTokens[] = $parent->toArray();
            }

            $childUrl = $this->urlPathGenerator->generate($pathTokens);
            $urlTransfer->setUrl($childUrl);
            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function removeUrl(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $path = $this->categoryTreeReader->getPath($categoryNode->getIdCategoryNode(), $locale);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNode->getIdCategoryNode();
        
        if ($this->urlFacade->hasUrl($categoryUrl)) {
            $url = $this->urlFacade->getUrlByPath($categoryUrl);
            $this->urlFacade->touchUrlDeleted($url->getIdUrl());
        }
    }
}
