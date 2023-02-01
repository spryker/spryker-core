<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Mapper;

use Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\CategoryCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryConditionsTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;

class GlueRequestCategoryMapper implements GlueRequestCategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCriteriaTransfer
     */
    public function mapGlueRequestTransferToCategoryCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCriteriaTransfer {
        $categoryCriteriaTransfer = new CategoryCriteriaTransfer();
        $categoryCriteriaTransfer->setPagination($glueRequestTransfer->getPagination());
        $categoryCriteriaTransfer->setSortCollection($glueRequestTransfer->getSortings());
        $categoryConditionsTransfer = new CategoryConditionsTransfer();

        if (!isset($glueRequestTransfer->getQueryFields()['filter'])) {
            return $categoryCriteriaTransfer;
        }
        foreach ($glueRequestTransfer->getQueryFields()['filter'] as $key => $value) {
            // Apply conditions to $categoryConditionsTransfer
        }
        $categoryCriteriaTransfer->setCategoryConditions($categoryConditionsTransfer);

        return $categoryCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer
     */
    public function mapGlueRequestTransferToCategoryCollectionDeleteCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCollectionDeleteCriteriaTransfer {
        $categoryDeleteCollectionCriteriaTransfer = new CategoryCollectionDeleteCriteriaTransfer();
        if (!isset($glueRequestTransfer->getQueryFields()['filter'])) {
            return $categoryDeleteCollectionCriteriaTransfer;
        }
        foreach ($glueRequestTransfer->getQueryFields()['filter'] as $key => $value) {
            // Apply conditions to $categoryDeleteCollectionCriteriaTransfer
        }

        return $categoryDeleteCollectionCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCriteriaTransfer
     */
    public function mapGlueGetRequestToCategoryCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCriteriaTransfer {
        $categoryCriteriaTransfer = $this->mapGlueGetCollectionRequestToCategoryCriteriaTransfer($glueRequestTransfer);

        $identifier = $glueRequestTransfer->getResourceOrFail()->getIdOrFail();

        $categoryConditions = $categoryCriteriaTransfer->getCategoryConditions();
        if (!$categoryConditions) {
            $categoryConditions = new CategoryConditionsTransfer();
            $categoryCriteriaTransfer->setCategoryConditions($categoryConditions);
        }

        $categoryConditions->addCategoryKey($identifier);

        return $categoryCriteriaTransfer;
    }

    /**
     * @inheritDoc
     */
    public function mapGlueGetCollectionRequestToCategoryCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCriteriaTransfer {
        $categoryCriteriaTransfer = new CategoryCriteriaTransfer();

        $categoryCriteriaTransfer->setSortCollection($glueRequestTransfer->getSortings());
        $categoryCriteriaTransfer->setPagination($glueRequestTransfer->getPagination());

        $categoryCriteriaTransfer->setCategoryConditions(
            (new CategoryConditionsTransfer())
                ->setWithParentCategory(true),
        );

        return $categoryCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer
     */
    public function mapGlueRequestToCategoryCollectionDeleteCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCollectionDeleteCriteriaTransfer {
        $categoryCollectionDeleteCriteriaTransfer = new CategoryCollectionDeleteCriteriaTransfer();
        $categoryCollectionDeleteCriteriaTransfer->setIsTransactional(false)->addCategoryKey($glueRequestTransfer->getResourceOrFail()->getIdOrFail());

        return $categoryCollectionDeleteCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionRequestTransfer
     */
    public function mapGlueRequestToCategoryCollectionRequestTransfer(
        CategoryTransfer $categoryTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCollectionRequestTransfer {
        $categoryCollectionRequestTransfer = new CategoryCollectionRequestTransfer();
        $categoryCollectionRequestTransfer->addCategory($categoryTransfer)->setIsTransactional(false);

        return $categoryCollectionRequestTransfer;
    }
}
