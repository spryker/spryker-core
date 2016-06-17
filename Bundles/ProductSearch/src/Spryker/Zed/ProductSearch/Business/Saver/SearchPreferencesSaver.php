<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Saver;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ProductSearchPreferencesTransfer;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMap;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class SearchPreferencesSaver implements SearchPreferencesSaverInterface
{

    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     */
    public function __construct(ProductSearchQueryContainerInterface $productSearchQueryContainer)
    {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     *
     * @return void
     */
    public function save(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer)
    {
        $this->productSearchQueryContainer->getConnection()->beginTransaction();

        $idProductAttributesMetadata = $productSearchPreferencesTransfer
            ->requireIdProductAttributesMetadata()
            ->getIdProductAttributesMetadata();

        $this->cleanProductSearchAttributeMap($idProductAttributesMetadata);

        $this
            ->addFullText($productSearchPreferencesTransfer, $idProductAttributesMetadata)
            ->addFullTextBoosted($productSearchPreferencesTransfer, $idProductAttributesMetadata)
            ->addSuggestionTerms($productSearchPreferencesTransfer, $idProductAttributesMetadata)
            ->addCompletionTerms($productSearchPreferencesTransfer, $idProductAttributesMetadata);

        /*
         * TODO: we need to touch all products to trigger collectors (search only if possible) to update the searchable data.
         * Maybe we'd need a different event to trigger the product search collector ("Apply search preferences" button in the UI).
         */

        $this->productSearchQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAttributesMetadata
     *
     * @return void
     */
    protected function cleanProductSearchAttributeMap($idProductAttributesMetadata)
    {
        $this
            ->productSearchQueryContainer
            ->queryProductSearchAttributeMapByFkProductAttributesMetadata($idProductAttributesMetadata)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     * @param int $idProductAttributesMetadata
     *
     * @return $this
     */
    protected function addFullText(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer, $idProductAttributesMetadata)
    {
        if ($productSearchPreferencesTransfer->getFullText() === true) {
            $this->createNewProductSearchAttributeMapRecord($idProductAttributesMetadata, PageIndexMap::FULL_TEXT);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     * @param int $idProductAttributesMetadata
     *
     * @return $this
     */
    protected function addFullTextBoosted(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer, $idProductAttributesMetadata)
    {
        if ($productSearchPreferencesTransfer->getFullTextBoosted() === true) {
            $this->createNewProductSearchAttributeMapRecord($idProductAttributesMetadata, PageIndexMap::FULL_TEXT_BOOSTED);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     * @param int $idProductAttributesMetadata
     *
     * @return $this
     */
    protected function addSuggestionTerms(ProductSearchPreferencesTransfer$productSearchPreferencesTransfer, $idProductAttributesMetadata)
    {
        if ($productSearchPreferencesTransfer->getSuggestionTerms() === true) {
            $this->createNewProductSearchAttributeMapRecord($idProductAttributesMetadata, PageIndexMap::SUGGESTION_TERMS);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     * @param int $idProductAttributesMetadata
     *
     * @return $this
     */
    protected function addCompletionTerms(ProductSearchPreferencesTransfer$productSearchPreferencesTransfer, $idProductAttributesMetadata)
    {
        if ($productSearchPreferencesTransfer->getCompletionTerms() === true) {
            $this->createNewProductSearchAttributeMapRecord($idProductAttributesMetadata, PageIndexMap::COMPLETION_TERMS);
        }

        return $this;
    }

    /**
     * @param int $idProductAttributesMetadata
     * @param string $targetField
     *
     * @return void
     */
    protected function createNewProductSearchAttributeMapRecord($idProductAttributesMetadata, $targetField)
    {
        $entity = new SpyProductSearchAttributeMap();
        $entity
            ->setFkProductAttributesMetadata($idProductAttributesMetadata)
            ->setTargetField($targetField);

        $entity->save();
    }

}
