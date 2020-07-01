<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet;

interface ProductLabelDataSetInterface
{
    public const COL_NAME = 'name';
    public const COL_IS_ACTIVE = 'is_active';
    public const COL_IS_DYNAMIC = 'is_dynamic';
    public const COL_IS_EXCLUSIVE = 'is_exclusive';
    public const COL_FRONT_END_REFERENCE = 'front_end_reference';
    public const COL_VALID_FROM = 'valid_from';
    public const COL_VALID_TO = 'valid_to';
    public const COL_PRODUCT_ABSTRACT_SKUS = 'product_abstract_skus';
    public const COL_PRIORITY = 'priority';

    public const COL_ID_PRODUCT_LABEL = 'id_product_label';
    public const COL_PRODUCT_ABSTRACT_IDS = 'product_abstract_ids';
}
