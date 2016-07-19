<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

interface AttributeReaderInterface
{

    /**
     * @param int $idAttribute
     * @param $idLocale
     * @param string $searchText
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getAttributeValues($idAttribute, $idLocale, $searchText = '', $offset = 0, $limit = 10);

}
