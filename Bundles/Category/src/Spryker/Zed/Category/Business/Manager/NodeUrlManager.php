<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Manager;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

/**
 * @deprecated Will be removed with next major release
 */
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
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @param \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface $categoryTreeReader
     * @param \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface $urlPathGenerator
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface $urlFacade
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct(
        CategoryTreeReaderInterface $categoryTreeReader,
        UrlPathGeneratorInterface $urlPathGenerator,
        CategoryToUrlInterface $urlFacade,
        CategoryQueryContainerInterface $categoryQueryContainer
    ) {
        $this->categoryTreeReader = $categoryTreeReader;
        $this->urlPathGenerator = $urlPathGenerator;
        $this->urlFacade = $urlFacade;
        $this->categoryQueryContainer = $categoryQueryContainer;
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

        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl($categoryUrl)
            ->setFkLocale($localeTransfer->requireIdLocale()->getIdLocale())
            ->setFkResourceCategorynode($categoryNodeTransfer->requireIdCategoryNode()->getIdCategoryNode());

        try {
            $this->urlFacade->createUrl($urlTransfer);
        } catch (UrlExistsException $e) {
            throw new CategoryUrlExistsException($e->getMessage(), $e->getCode(), $e);
        }
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

        if (!$this->hasCategoryNodeUrl($idCategoryNode, $localeTransfer)) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer
                ->setUrl($categoryUrl)
                ->setFkLocale($localeTransfer->getIdLocale())
                ->setFkResourceCategorynode($idCategoryNode);

            $this->urlFacade->createUrl($urlTransfer);
        } else {
            $urlTransfer = $this->getCategoryNodeUrl($idCategoryNode, $localeTransfer);
            $urlTransfer->setUrl($categoryUrl);

            $this->urlFacade->updateUrl($urlTransfer);
        }

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
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryClosureTable[] $children */
        $children = $this->categoryTreeReader->getPathChildren($categoryNodeTransfer->getIdCategoryNode());
        foreach ($children as $child) {
            if (!$this->hasCategoryNodeUrl($child->getFkCategoryNodeDescendant(), $localeTransfer)) {
                continue;
            }
            $urlTransfer = $this->getCategoryNodeUrl($child->getFkCategoryNodeDescendant(), $localeTransfer);

            $childUrl = $this->generateChildUrl($child->getFkCategoryNodeDescendant(), $localeTransfer);

            $urlTransfer
                ->setUrl($childUrl)
                ->setFkResourceCategorynode($child->getFkCategoryNodeDescendant())
                ->setFkLocale($localeTransfer->getIdLocale());

            $this->urlFacade->updateUrl($urlTransfer);
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
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    public function removeUrl(NodeTransfer $categoryNodeTransfer)
    {
        $idNode = $categoryNodeTransfer->getIdCategoryNode();
        $urlTransfers = $this->getCategoryNodeUrlCollection($idNode);

        foreach ($urlTransfers as $urlTransfer) {
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
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryClosureTable[] $parents */
        $parents = $this->categoryTreeReader->getPathParents($idChild, $localeTransfer->getIdLocale());
        $pathTokens = [];
        foreach ($parents as $parent) {
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

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    protected function hasCategoryNodeUrl($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        return $this->categoryQueryContainer
            ->queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $localeTransfer->getIdLocale())
            ->count() > 0;
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function getCategoryNodeUrl($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        $urlEntity = $this->categoryQueryContainer
            ->queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $localeTransfer->getIdLocale())
            ->findOne();

        return $this->mapUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idNode
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    protected function getCategoryNodeUrlCollection($idNode)
    {
        $categoryNodeUrlCollection = [];

        $urlEntities = $this->categoryQueryContainer
            ->queryResourceUrlByCategoryNodeId($idNode)
            ->find();

        foreach ($urlEntities as $urlEntity) {
            $categoryNodeUrlCollection[] = $this->mapUrlEntityToTransfer($urlEntity);
        }

        return $categoryNodeUrlCollection;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function mapUrlEntityToTransfer(SpyUrl $urlEntity)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }
}
