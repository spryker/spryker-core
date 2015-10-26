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
        $categoryUrl = $this->generateUrlFromPathTokens($path);
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
        $categoryUrl = $this->generateUrlFromPathTokens($path);
        $idNode = $categoryNodeTransfer->getIdCategoryNode();

        $urlTransfer = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($idNode, $localeTransfer);

        if (!$urlTransfer) {
            $urlTransfer = new UrlTransfer();
            $this->updateTransferUrl($urlTransfer, $categoryUrl, $idNode, $localeTransfer->getIdLocale());
        } else {
            $this->updateTransferUrl($urlTransfer, $categoryUrl);
        }

        $this->urlFacade->saveUrlAndTouch($urlTransfer);

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

            $childUrl = $this->generateChildUrl($child->getFkCategoryNodeDescendant());
            $this->updateTransferUrl($urlTransfer, $childUrl, $child->getFkCategoryNodeDescendant(), $localeTransfer->getIdLocale());
            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }
    }

    /**
     * @param UrlTransfer $urlTransfer
     * @param string $url
     * @param int $idResource
     * @param int $idLocale
     *
     * @return void
     */
    protected function updateTransferUrl(UrlTransfer $urlTransfer, $url, $idResource=null, $idLocale=null)
    {
        $urlTransfer->setResourceType(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE);

        if ($idResource !== null) {
            $urlTransfer->setResourceId($idResource);
        }

        if ($idLocale !== null) {
            $urlTransfer->setFkLocale($idLocale);
        }

        $urlTransfer->setUrl($url);
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

    /**
     * @param int $idChild
     *
     * @return string
     */
    protected function generateChildUrl($idChild)
    {
        $parentList = $this->categoryTreeReader->getPathParents($idChild);
        $pathTokens = [];
        foreach ($parentList as $parent) {
            /* @var SpyCategoryClosureTable $parent */
            $pathTokens[] = $parent->toArray();
        }

        return $this->generateUrlFromPathTokens($pathTokens);
    }

    /**
     * @param array $pathTokens
     *
     * @return string
     */
    protected function generateUrlFromPathTokens(array $pathTokens)
    {
        return $this->urlPathGenerator->generate($pathTokens);
    }

}
