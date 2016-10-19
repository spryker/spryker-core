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

            $this->createUrl($categoryNodeUrl, $localeTransfer, $categoryNodeTransfer);
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
        $pathParts = $this->queryContainer->queryPath(
            $categoryNodeTransfer->getIdCategoryNode(),
            $localeTransfer->getIdLocale()
        )->find();

        return $this->urlPathGenerator->generate($pathParts);
    }

    /**
     * @param string $categoryNodeUrl
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     *
     * @return void
     */
    protected function createUrl($categoryNodeUrl, LocaleTransfer $localeTransfer, NodeTransfer $categoryNodeTransfer)
    {
        try {
            $urlTransfer = $this->urlFacade->createUrl(
                $categoryNodeUrl,
                $localeTransfer,
                CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE,
                $categoryNodeTransfer->getIdCategoryNode()
            );
            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        } catch (UrlExistsException $e) {
            throw new CategoryUrlExistsException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        // Updating URLs not yet supported
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
