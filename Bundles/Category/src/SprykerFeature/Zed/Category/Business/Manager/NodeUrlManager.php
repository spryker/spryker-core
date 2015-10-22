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
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function createUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $path = $this->categoryTreeReader->getPath($categoryNodeTransfer->getIdCategoryNode(), $localeTransfer);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNodeTransfer->getIdCategoryNode();

        $url = $this->urlFacade->createUrl($categoryUrl, $localeTransfer, CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idNode);
        $this->urlFacade->touchUrlActive($url->getIdUrl());
    }

    /**
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function updateUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $path = $this->categoryTreeReader->getPath($categoryNodeTransfer->getIdCategoryNode(), $localeTransfer);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNodeTransfer->getIdCategoryNode();

        $url = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($idNode, $localeTransfer);

        if (!$url) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setFkPage(null);
            $urlTransfer->setResourceId($idNode);
            $urlTransfer->setResourceType(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE);
            $urlTransfer->setUrl($categoryUrl);
            $urlTransfer->setFkLocale($localeTransfer->getIdLocale());

            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        } else {
            $url->setUrl($categoryUrl);
            $this->urlFacade->saveUrlAndTouch($url);
        }

        $this->updateChildrenUrls($categoryNodeTransfer, $localeTransfer);
    }

    /**
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function updateChildrenUrls(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $children = $this->categoryTreeReader->getPathChildren($categoryNodeTransfer->getIdCategoryNode());
        foreach ($children as $child) {
            /* @var SpyCategoryClosureTable $child */
            $urlTransfer = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($child->getFkCategoryNodeDescendant(), $localeTransfer);
            if (!$urlTransfer) {
                continue;
            }

            $parentList = $this->categoryTreeReader->getPathParents($child->getFkCategoryNodeDescendant());
            $pathTokens = [];
            foreach ($parentList as $parent) {
                /* @var SpyCategoryClosureTable $parent */
                $pathTokens[] = $parent->toArray();
            }

            $childUrl = $this->urlPathGenerator->generate($pathTokens);
            $urlTransfer->setUrl($childUrl);
            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }
    }

    /**
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function removeUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $idNode = $categoryNodeTransfer->getIdCategoryNode();
        $urlTransfer = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($idNode, $localeTransfer);

        if ($urlTransfer) {
            $this->urlFacade->deleteUrl($urlTransfer);
        }
    }

}
