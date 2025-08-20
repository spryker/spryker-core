<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\DataSet;

interface SspModelDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_REFERENCE = 'reference';

    /**
     * @var string
     */
    public const COLUMN_NAME = 'name';

    /**
     * @var string
     */
    public const COLUMN_CODE = 'code';

    /**
     * @var string
     */
    public const COLUMN_IMAGE_URL = 'image_url';
}
