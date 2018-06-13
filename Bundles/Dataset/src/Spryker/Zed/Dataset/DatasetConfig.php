<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DatasetConfig extends AbstractBundleConfig
{
    public const DATASET_FILE_SIZE = 'DATASET_FILE_SIZE';
    protected const DEFAULT_SIZE = '1M';

    /**
     * @return string
     */
    public function getMaxFileSize(): string
    {
        return $path = $this->getConfig()->hasValue(static::DATASET_FILE_SIZE) ?
            $this->getConfig()->get(static::DATASET_FILE_SIZE) : static::DEFAULT_SIZE;
    }
}
