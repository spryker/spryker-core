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

        $idProductAttributeKey = $productSearchPreferencesTransfer
            ->requireIdProductAttributeKey()
            ->getIdProductAttributeKey();

        $this->cleanProductSearchAttributeMap($idProductAttributeKey);

        $this
            ->addFullText($productSearchPreferencesTransfer, $idProductAttributeKey)
            ->addFullTextBoosted($productSearchPreferencesTransfer, $idProductAttributeKey)
            ->addSuggestionTerms($productSearchPreferencesTransfer, $idProductAttributeKey)
            ->addCompletionTerms($productSearchPreferencesTransfer, $idProductAttributeKey);

        /*
         * TODO: we need to touch all products to trigger collectors (search only if possible) to update the searchable data.
         * Maybe we'd need a different event to trigger the product search collector ("Apply search preferences" button in the UI).
         */

        $this->productSearchQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAttributeKey
     *
     * @return void
     */
    protected function cleanProductSearchAttributeMap($idProductAttributeKey)
    {
        $this
            ->productSearchQueryContainer
            ->queryProductSearchAttributeMapByFkProductAttributeKey($idProductAttributeKey)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     * @param int $idProductAttributeKey
     *
     * @return $this
     */
    protected function addFullText(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer, $idProductAttributeKey)
    {
        if ($productSearchPreferencesTransfer->getFullText() === true) {
            $this->createNewProductSearchAttributeMapRecord($idProductAttributeKey, PageIndexMap::FULL_TEXT);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     * @param int $idProductAttributeKey
     *
     * @return $this
     */
    protected function addFullTextBoosted(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer, $idProductAttributeKey)
    {
        if ($productSearchPreferencesTransfer->getFullTextBoosted() === true) {
            $this->createNewProductSearchAttributeMapRecord($idProductAttributeKey, PageIndexMap::FULL_TEXT_BOOSTED);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     * @param int $idProductAttributeKey
     *
     * @return $this
     */
    protected function addSuggestionTerms(ProductSearchPreferencesTransfer$productSearchPreferencesTransfer, $idProductAttributeKey)
    {
        if ($productSearchPreferencesTransfer->getSuggestionTerms() === true) {
            $this->createNewProductSearchAttributeMapRecord($idProductAttributeKey, PageIndexMap::SUGGESTION_TERMS);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     * @param int $idProductAttributeKey
     *
     * @return $this
     */
    protected function addCompletionTerms(ProductSearchPreferencesTransfer$productSearchPreferencesTransfer, $idProductAttributeKey)
    {
        if ($productSearchPreferencesTransfer->getCompletionTerms() === true) {
            $this->createNewProductSearchAttributeMapRecord($idProductAttributeKey, PageIndexMap::COMPLETION_TERMS);
        }

        return $this;
    }

    /**
     * @param int $idProductAttributeKey
     * @param string $targetField
     *
     * @return void
     */
    protected function createNewProductSearchAttributeMapRecord($idProductAttributeKey, $targetField)
    {
        $entity = new SpyProductSearchAttributeMap();
        $entity
            ->setFkProductAttributeKey($idProductAttributeKey)
            ->setTargetField($targetField);

        $entity->save();
    }

}
