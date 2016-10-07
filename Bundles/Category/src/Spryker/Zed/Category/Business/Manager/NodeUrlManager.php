<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Manager;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

class NodeUrlManager implements NodeUrlManagerInterface
{

    /**
     * @var \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface
     */
    protected $categoryTreeReader;

    /**
     * @var \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface
     */
    protected $urlPathGenerator;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface $categoryTreeReader
     * @param \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface $urlPathGenerator
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface $urlFacade
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
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     *
     * @return void
     */
    public function createUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $path = $this->categoryTreeReader->getPath($categoryNodeTransfer->getIdCategoryNode(), $localeTransfer);
        $categoryUrl = $this->generateUrlFromPathTokens($path);
        $idNode = $categoryNodeTransfer->getIdCategoryNode();

        try {
            $urlTransfer = $this->urlFacade->createUrl($categoryUrl, $localeTransfer, CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE, $idNode);
        } catch (UrlExistsException $e) {
            throw new CategoryUrlExistsException($e->getMessage(), $e->getCode(), $e);
        }

        $this->updateTransferUrl($urlTransfer, $categoryUrl, $idNode, $localeTransfer->getIdLocale());
        $this->urlFacade->saveUrlAndTouch($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function updateUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $idCategoryNode = $categoryNodeTransfer->getIdCategoryNode();
        $path = $this->categoryTreeReader->getPath($idCategoryNode, $localeTransfer);
        $categoryUrl = $this->generateUrlFromPathTokens($path);

        if (!$this->urlFacade->hasResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, $localeTransfer)) {
            $urlTransfer = new UrlTransfer();
            $this->updateTransferUrl($urlTransfer, $categoryUrl, $idCategoryNode, $localeTransfer->getIdLocale());
        } else {
            $urlTransfer = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, $localeTransfer);
            $this->updateTransferUrl($urlTransfer, $categoryUrl);
        }

        $this->urlFacade->saveUrlAndTouch($urlTransfer);

        $this->updateChildrenUrls($categoryNodeTransfer, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function updateChildrenUrls(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $children = $this->categoryTreeReader->getPathChildren($categoryNodeTransfer->getIdCategoryNode());
        foreach ($children as $child) {
            /** @var \Orm\Zed\Category\Persistence\SpyCategoryClosureTable $child */
            if (!$this->urlFacade->hasResourceUrlByCategoryNodeIdAndLocale($child->getFkCategoryNodeDescendant(), $localeTransfer)) {
                continue;
            }
            $urlTransfer = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($child->getFkCategoryNodeDescendant(), $localeTransfer);

            $childUrl = $this->generateChildUrl($child->getFkCategoryNodeDescendant(), $localeTransfer);
            $this->updateTransferUrl($urlTransfer, $childUrl, $child->getFkCategoryNodeDescendant(), $localeTransfer->getIdLocale());
            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param string $url
     * @param int|null $idResource
     * @param int|null $idLocale
     *
     * @return void
     */
    protected function updateTransferUrl(UrlTransfer $urlTransfer, $url, $idResource = null, $idLocale = null)
    {
        $urlTransfer->setResourceType(CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE);

        if ($idResource !== null) {
            $urlTransfer->setResourceId($idResource);
        }

        if ($idLocale !== null) {
            $urlTransfer->setFkLocale($idLocale);
        }

        $urlTransfer->setUrl($url);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    public function removeUrl(NodeTransfer $categoryNodeTransfer)
    {
        $idNode = $categoryNodeTransfer->getIdCategoryNode();
        $urls = $this->urlFacade->getResourceUrlCollectionByCategoryNodeId($idNode);

        foreach ($urls as $urlTransfer) {
            $this->urlFacade->deleteUrl($urlTransfer);
        }
    }

    /**
     * @param int $idChild
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function generateChildUrl($idChild, LocaleTransfer $localeTransfer)
    {
        $parentList = $this->categoryTreeReader->getPathParents($idChild, $localeTransfer->getIdLocale());
        $pathTokens = [];
        foreach ($parentList as $parent) {
            /** @var \Orm\Zed\Category\Persistence\SpyCategoryClosureTable $parent */
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
