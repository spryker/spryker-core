<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextDataImport\Business\DataSet;

interface StoreContextDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const COLUMN_APPLICATION_CONTEXT_COLLECTION = 'appication_context_collection';

    /**
     * @var string
     */
    public const FK_STORE = 'fk_store';
}
