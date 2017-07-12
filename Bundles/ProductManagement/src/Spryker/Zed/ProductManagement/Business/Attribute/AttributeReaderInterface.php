<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

interface AttributeReaderInterface
{

    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getAttributeValueSuggestions($idProductManagementAttribute, $idLocale, $searchText = '', $offset = 0, $limit = 10);

    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     *
     * @return int
     */
    public function getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText = '');

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedKeys($searchText = '', $limit = 10);

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection();

}
