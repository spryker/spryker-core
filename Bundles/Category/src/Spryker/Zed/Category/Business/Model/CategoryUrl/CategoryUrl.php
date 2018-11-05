<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryUrl;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryUrl implements CategoryUrlInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface
     */
    protected $urlPathGenerator;

    /**
     * @var \Spryker\Zed\Category\Dependency\Plugin\CategoryUrlPathPluginInterface[]
     */
    protected $categoryUrlPathPlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface $urlFacade
     * @param \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface $urlPathGenerator
     * @param array $categoryUrlPathPlugins
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryToUrlInterface $urlFacade,
        UrlPathGeneratorInterface $urlPathGenerator,
        array $categoryUrlPathPlugins = []
    ) {
        $this->queryContainer = $queryContainer;
        $this->urlFacade = $urlFacade;
        $this->urlPathGenerator = $urlPathGenerator;
        $this->categoryUrlPathPlugins = $categoryUrlPathPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeTransfer = $categoryTransfer->requireCategoryNode()->getCategoryNode();
        $localizedCategoryAttributesTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedCategoryAttributesTransferCollection as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->requireLocale()->getLocale();
            $this->saveLocalizedUrlForNode($categoryNodeTransfer, $localeTransfer, $categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function saveLocalizedUrlForNode(
        NodeTransfer $categoryNodeTransfer,
        LocaleTransfer $localeTransfer,
        CategoryTransfer $categoryTransfer
    ) {
        $urlTransfer = $this->getUrlTransferForNode($categoryNodeTransfer, $localeTransfer);

        $categoryNodeUrl = $this->build($categoryNodeTransfer, $localeTransfer);
        $urlTransfer->setUrl($categoryNodeUrl);

        if ($this->urlFacade->hasUrl($urlTransfer)) {
            return;
        }

        if ($urlTransfer->getIdUrl()) {
            $this->urlFacade->updateUrl($urlTransfer);
            return;
        }

        $this->urlFacade->createUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function build(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $pathParts = $this->getUrlPathPartsForCategoryNode($categoryNodeTransfer, $localeTransfer);

        return $this->urlPathGenerator->generate($pathParts);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getUrlPathPartsForCategoryNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $pathParts = $this
            ->queryContainer
            ->queryPath(
                $categoryNodeTransfer->requireIdCategoryNode()->getIdCategoryNode(),
                $localeTransfer->requireIdLocale()->getIdLocale()
            )
            ->find();

        foreach ($this->categoryUrlPathPlugins as $categoryUrlPathPlugin) {
            $pathParts = $categoryUrlPathPlugin->update($pathParts, $localeTransfer);
        }

        return $pathParts;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function getUrlTransferForNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setFkLocale($localeTransfer->requireIdLocale()->getIdLocale())
            ->setFkResourceCategorynode($categoryNodeTransfer->requireIdCategoryNode()->getIdCategoryNode());

        $urlEntity = $this->findUrlForNode(
            $categoryNodeTransfer->requireIdCategoryNode()->getIdCategoryNode(),
            $localeTransfer
        );
        if ($urlEntity) {
            $urlTransfer->setIdUrl($urlEntity->getIdUrl());
        }

        return $urlTransfer;
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl|null
     */
    protected function findUrlForNode($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        $urlEntity = $this
            ->queryContainer
            ->queryUrlByIdCategoryNode($idCategoryNode)
            ->filterByFkLocale($localeTransfer->requireIdLocale()->getIdLocale())
            ->findOne();

        return $urlEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function touchUrl(CategoryTransfer $categoryTransfer, UrlTransfer $urlTransfer)
    {
        if ($categoryTransfer->getIsActive()) {
            $this->urlFacade->activateUrl($urlTransfer);
        } else {
            $this->urlFacade->deactivateUrl($urlTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeTransfer = $categoryTransfer->requireCategoryNode()->getCategoryNode();
        $localizedCategoryAttributesTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedCategoryAttributesTransferCollection as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->requireLocale()->getLocale();

            $this->saveLocalizedUrlForNode($categoryNodeTransfer, $localeTransfer, $categoryTransfer);
            $this->updateAllChildrenUrls($categoryNodeTransfer, $localeTransfer, $categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function updateAllChildrenUrls(
        NodeTransfer $categoryNodeTransfer,
        LocaleTransfer $localeTransfer,
        CategoryTransfer $categoryTransfer
    ) {
        $childNodeCollection = $this->getAllChildNodes($categoryNodeTransfer, $localeTransfer);

        foreach ($childNodeCollection as $childNodeEntity) {
            $childNodeTransfer = (new NodeTransfer())->fromArray($childNodeEntity->toArray(), true);
            $this->saveLocalizedUrlForNode($childNodeTransfer, $localeTransfer, $categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getAllChildNodes(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $onlyOneLevel = false;

        return $this
            ->queryContainer
            ->queryChildren(
                $categoryNodeTransfer->requireIdCategoryNode()->getIdCategoryNode(),
                $localeTransfer->requireIdLocale()->getIdLocale(),
                $onlyOneLevel
            )
            ->find();
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $categoryNodeCollection = $this
            ->queryContainer
            ->queryAllNodesByCategoryId($idCategory)
            ->find();

        foreach ($categoryNodeCollection as $categoryNodeEntity) {
            $this->deleteUrlsForCategoryNode($categoryNodeEntity->getIdCategoryNode());
        }
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteUrlsForCategoryNode($idCategoryNode)
    {
        $urlCollection = $this
            ->queryContainer
            ->queryUrlByIdCategoryNode($idCategoryNode)
            ->find();

        foreach ($urlCollection as $urlEntity) {
            $urlTransfer = $this->getUrlTransferFromEntity($urlEntity);
            $this->urlFacade->deleteUrl($urlTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function getUrlTransferFromEntity(SpyUrl $urlEntity)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }
}
