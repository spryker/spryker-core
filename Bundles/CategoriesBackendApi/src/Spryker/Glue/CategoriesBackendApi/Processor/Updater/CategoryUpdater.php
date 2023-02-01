<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Processor\Updater;

use Generated\Shared\Transfer\ApiCategoryAttributesTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Processor\Reader\CategoryReaderInterface;
use Symfony\Component\HttpFoundation\Response;

class CategoryUpdater implements CategoryUpdaterInterface
{
    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface
     */
    protected CategoriesBackendApiToCategoryFacadeInterface $categoryFacade;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface
     */
    protected GlueRequestCategoryMapperInterface $glueRequestCategoryMapper;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface
     */
    protected GlueResponseCategoryMapperInterface $glueResponseCategoryMapper;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapperInterface
     */
    protected CategoryMapperInterface $categoryMapper;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Processor\Reader\CategoryReaderInterface
     */
    protected CategoryReaderInterface $categoryReader;

    /**
     * @param \Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface $glueRequestCategoryMapper
     * @param \Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface $glueResponseCategoryMapper
     * @param \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapperInterface $categoryMapper
     * @param \Spryker\Glue\CategoriesBackendApi\Processor\Reader\CategoryReaderInterface $categoryReader
     */
    public function __construct(
        GlueRequestCategoryMapperInterface $glueRequestCategoryMapper,
        GlueResponseCategoryMapperInterface $glueResponseCategoryMapper,
        CategoriesBackendApiToCategoryFacadeInterface $categoryFacade,
        CategoryMapperInterface $categoryMapper,
        CategoryReaderInterface $categoryReader
    ) {
        $this->glueResponseCategoryMapper = $glueResponseCategoryMapper;
        $this->glueRequestCategoryMapper = $glueRequestCategoryMapper;
        $this->categoryFacade = $categoryFacade;
        $this->categoryMapper = $categoryMapper;
        $this->categoryReader = $categoryReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiCategoryAttributesTransfer $apiCategoryAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateCategory(ApiCategoryAttributesTransfer $apiCategoryAttributesTransfer, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $categoryCriteriaTransfer = $this->glueRequestCategoryMapper->mapGlueGetRequestToCategoryCriteriaTransfer($glueRequestTransfer);
        $categoryCollectionTransfer = $this->categoryFacade->getCategoryCollection($categoryCriteriaTransfer);

        if ($categoryCollectionTransfer->getCategories()->count() === 0) {
            return (new GlueResponseTransfer())
                ->setHttpStatus(Response::HTTP_NOT_FOUND)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setStatus(Response::HTTP_NOT_FOUND)
                        ->setMessage(Response::$statusTexts[Response::HTTP_NOT_FOUND]),
                );
        }

        /** @var \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer */
        $categoryTransfer = $categoryCollectionTransfer->getCategories()->offsetGet(0);

        $this->categoryMapper->mapApiCategoryAttributesTransferToCategoryTransfer($apiCategoryAttributesTransfer, $categoryTransfer);

        $categoryCollectionRequestTransfer = $this->glueRequestCategoryMapper->mapGlueRequestToCategoryCollectionRequestTransfer($categoryTransfer, $glueRequestTransfer);

        $categoryCollectionResponseTransfer = $this->categoryFacade->updateCategoryCollection($categoryCollectionRequestTransfer);

        if ($categoryCollectionResponseTransfer->getErrors()->count() > 0) {
            return $this->glueResponseCategoryMapper->mapCategoryCollectionResponseTransferToSingleResourceGlueResponseTransfer($categoryCollectionResponseTransfer);
        }

        return $this->categoryReader->getCategory($glueRequestTransfer);
    }
}
