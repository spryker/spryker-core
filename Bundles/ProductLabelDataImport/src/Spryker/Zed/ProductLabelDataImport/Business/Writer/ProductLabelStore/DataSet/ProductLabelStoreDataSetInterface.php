<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabelStore\DataSet;

interface ProductLabelStoreDataSetInterface
{
    public const COL_NAME = 'name';
    public const COL_STORE_NAME = 'store_name';

    public const COL_ID_PRODUCT_LABEL = 'id_product_label';
    public const COL_ID_STORE = 'id_store';
}
