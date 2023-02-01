<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Processor\Reader;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryImageFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface
     */
    protected GlueRequestCategoryMapperInterface $glueRequestCategoryMapper;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface
     */
    protected GlueResponseCategoryMapperInterface $glueResponseCategoryMapper;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface
     */
    protected CategoriesBackendApiToCategoryFacadeInterface $categoryFacade;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryImageFacadeInterface
     */
    protected CategoriesBackendApiToCategoryImageFacadeInterface $categoryImageFacade;

    /**
     * @param \Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface $glueRequestCategoryMapper
     * @param \Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface $glueResponseCategoryMapper
     * @param \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryImageFacadeInterface $categoryImageFacade
     */
    public function __construct(
        GlueRequestCategoryMapperInterface $glueRequestCategoryMapper,
        GlueResponseCategoryMapperInterface $glueResponseCategoryMapper,
        CategoriesBackendApiToCategoryFacadeInterface $categoryFacade,
        CategoriesBackendApiToCategoryImageFacadeInterface $categoryImageFacade
    ) {
        $this->glueRequestCategoryMapper = $glueRequestCategoryMapper;
        $this->glueResponseCategoryMapper = $glueResponseCategoryMapper;
        $this->categoryFacade = $categoryFacade;
        $this->categoryImageFacade = $categoryImageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCategory(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $categoryCriteriaTransfer = $this->glueRequestCategoryMapper
            ->mapGlueGetRequestToCategoryCriteriaTransfer($glueRequestTransfer);

        $categoryCollectionTransfer = $this->readCategoryCollection($categoryCriteriaTransfer);

        return $this->glueResponseCategoryMapper
            ->mapCategoryCollectionTransferToSingleResourceGlueResponseTransfer($categoryCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCategoryCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $categoryCriteriaTransfer = $this->glueRequestCategoryMapper
            ->mapGlueGetCollectionRequestToCategoryCriteriaTransfer($glueRequestTransfer);

        $categoryCollectionTransfer = $this->readCategoryCollection($categoryCriteriaTransfer);

        return $this->glueResponseCategoryMapper
            ->mapCategoryCollectionTransferToGlueResponseTransfer($categoryCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    protected function readCategoryCollection(CategoryCriteriaTransfer $categoryCriteriaTransfer): CategoryCollectionTransfer
    {
        $categoryCollectionTransfer = $this->categoryFacade->getCategoryCollection($categoryCriteriaTransfer);

        // TODO: support expanding collection
        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $this->categoryImageFacade->expandCategoryWithImageSets($categoryTransfer);
        }

        return $categoryCollectionTransfer;
    }
}
