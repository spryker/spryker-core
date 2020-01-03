<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Business\Model\DataSet;

interface ContentProductSetDataSetInterface
{
    public const COLUMN_KEY = 'key';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_PRODUCT_SET_KEY = 'product_set_key';
    public const COLUMN_PRODUCT_SET_ID = 'product_set_id';
    public const COLUMN_PRODUCT_SET_KEY_DEFAULT = 'product_set_key.default';
    public const CONTENT_LOCALIZED_PRODUCT_SET_TERMS = 'content_localized_product_set_terms';
}
