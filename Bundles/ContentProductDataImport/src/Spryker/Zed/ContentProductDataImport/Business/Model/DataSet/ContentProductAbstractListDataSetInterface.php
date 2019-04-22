<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\DataSet;

interface ContentProductAbstractListDataSetInterface
{
    public const CONTENT_PRODUCT_ABSTRACT_LIST_KEY = 'key';
    public const CONTENT_LOCALIZED_PRODUCT_ABSTRACT_LIST_TERMS = 'content_localized_product_abstract_list_terms';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_SKUS = 'skus';
    public const COLUMN_IDS = 'ids';
    public const COLUMN_LOCALES = 'locales';
    public const COLUMN_DEFAULT_SKUS = 'skus.default';
}
