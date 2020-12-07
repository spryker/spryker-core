<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DetermineStrategy;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;

interface DetermineStrategyInterface
{
    /**
     * Specification:
     * - Returns DataSetWriter that is applicable for current config, null otherwise
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function getApplicableDataSetWriter(): DataSetWriterInterface;
}
