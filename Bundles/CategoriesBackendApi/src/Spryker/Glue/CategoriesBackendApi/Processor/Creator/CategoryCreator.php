<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Processor\Creator;

use Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface;

class CategoryCreator implements CategoryCreatorInterface
{
    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface
     */
    protected CategoriesBackendApiToCategoryFacadeInterface $categoryFacade;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapperInterface
     */
    protected CategoryMapperInterface $categoryMapper;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface
     */
    protected GlueRequestCategoryMapperInterface $glueRequestCategoryMapper;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface
     */
    protected GlueResponseCategoryMapperInterface $glueResponseCategoryMapper;

    /**
     * @param \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapperInterface $categoryMapper
     * @param \Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface $glueRequestCategoryMapper
     * @param \Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface $glueResponseCategoryMapper
     */
    public function __construct(
        CategoriesBackendApiToCategoryFacadeInterface $categoryFacade,
        CategoryMapperInterface $categoryMapper,
        GlueRequestCategoryMapperInterface $glueRequestCategoryMapper,
        GlueResponseCategoryMapperInterface $glueResponseCategoryMapper
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->categoryMapper = $categoryMapper;
        $this->glueRequestCategoryMapper = $glueRequestCategoryMapper;
        $this->glueResponseCategoryMapper = $glueResponseCategoryMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createCategory(
        CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $categoryTransfer = $this->categoryMapper->mapCategoriesBackendApiAttributesTransferToCategoryTransfer($categoriesBackendApiAttributesTransfer, new CategoryTransfer());
        $categoryCollectionRequestTransfer = $this->glueRequestCategoryMapper->mapGlueRequestToCategoryCollectionRequestTransfer($categoryTransfer, $glueRequestTransfer);
        $categoryCollectionResponseTransfer = $this->categoryFacade->createCategoryCollection($categoryCollectionRequestTransfer);

        return $this->glueResponseCategoryMapper->mapCategoryCollectionResponseTransferToGlueResponseTransfer($categoryCollectionResponseTransfer);
    }
}
