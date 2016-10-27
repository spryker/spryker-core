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
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

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
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface $urlFacade
     * @param \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface $urlPathGenerator
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryToUrlInterface $urlFacade,
        UrlPathGeneratorInterface $urlPathGenerator
    ) {
        $this->queryContainer = $queryContainer;
        $this->urlFacade = $urlFacade;
        $this->urlPathGenerator = $urlPathGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeTransfer = $categoryTransfer->getCategoryNode();
        $localizedCategoryAttributesTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedCategoryAttributesTransferCollection as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->getLocale();
            $categoryNodeUrl = $this->build($categoryNodeTransfer, $localeTransfer);

            $this->createUrl($categoryNodeUrl, $localeTransfer, $categoryNodeTransfer, $categoryTransfer);
        }
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
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getUrlPathPartsForCategoryNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $pathParts = $this->queryContainer->queryPath(
            $categoryNodeTransfer->getIdCategoryNode(),
            $localeTransfer->getIdLocale()
        )->find();

        return $pathParts;
    }

    /**
     * @param string $categoryNodeUrl
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     *
     * @return void
     */
    protected function createUrl(
        $categoryNodeUrl,
        LocaleTransfer $localeTransfer,
        NodeTransfer $categoryNodeTransfer,
        CategoryTransfer $categoryTransfer
    ) {
        try {
            $urlTransfer = $this->urlFacade->createUrl(
                $categoryNodeUrl,
                $localeTransfer,
                CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE,
                $categoryNodeTransfer->getIdCategoryNode()
            );
            $this->urlFacade->saveUrlAndTouch($urlTransfer);
            $this->touchUrl($categoryTransfer, $urlTransfer);
        } catch (UrlExistsException $e) {
            throw new CategoryUrlExistsException($e->getMessage(), $e->getCode(), $e);
        }
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
            $this->urlFacade->touchUrlActive($urlTransfer->getIdUrl());
        } else {
            $this->urlFacade->touchUrlDeleted($urlTransfer->getIdUrl());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeTransfer = $categoryTransfer->getCategoryNode();
        $localizedCategoryAttributesTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedCategoryAttributesTransferCollection as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->getLocale();

            $this->updateLocalizedUrlForNode($categoryNodeTransfer, $localeTransfer, $categoryTransfer);
            $this->updateAllChildrenUrls($categoryNodeTransfer, $localeTransfer, $categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     *
     * @return void
     */
    public function updateLocalizedUrlForNode(
        NodeTransfer $categoryNodeTransfer,
        LocaleTransfer $localeTransfer,
        CategoryTransfer $categoryTransfer
    ) {
        $urlTransfer = $this->getUrlTransferForNode($categoryNodeTransfer, $localeTransfer);

        $categoryNodeUrl = $this->build($categoryNodeTransfer, $localeTransfer);
        $urlTransfer->setUrl($categoryNodeUrl);

        try {
            $this->urlFacade->saveUrlAndTouch($urlTransfer);
            $this->touchUrl($categoryTransfer, $urlTransfer);
        } catch (UrlExistsException $exception) {
            throw new CategoryUrlExistsException($exception->getMessage(), $exception->getCode(), $exception);
        }
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
        $urlTransfer->setResourceType(CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE);
        $urlTransfer->setResourceId($categoryNodeTransfer->getIdCategoryNode());
        $urlTransfer->setFkLocale($localeTransfer->getIdLocale());

        $urlEntity = $this->findUrlForNode($categoryNodeTransfer->getIdCategoryNode(), $localeTransfer);
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
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->findOne();

        return $urlEntity;
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
            $this->updateLocalizedUrlForNode($childNodeTransfer, $localeTransfer, $categoryTransfer);
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
                $categoryNodeTransfer->getIdCategoryNode(),
                $localeTransfer->getIdLocale(),
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
            $urlCollection = $this
                ->queryContainer
                ->queryUrlByIdCategoryNode($categoryNodeEntity->getIdCategoryNode())
                ->find();

            foreach ($urlCollection as $urlEntity) {
                $urlTransfer = (new UrlTransfer())->fromArray($urlEntity->toArray(), true);
                $this->urlFacade->deleteUrl($urlTransfer);
            }
        }
    }

}
