<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\DataSet;

interface ContentProductAbstractListDataSetInterface
{
    public const CONTENT_PROCUCT_ABSTRACT_LIST_KEY = 'key';
    public const CONTENT_LOCALIZED_ITEMS = 'content_localized_items';
    public const COLUMN_ID_CONTENT = 'id_content';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_SKUS = 'skus';
    public const COLUMN_IDS = 'ids';
    public const COLUMN_LOCALES = 'locales';
}
