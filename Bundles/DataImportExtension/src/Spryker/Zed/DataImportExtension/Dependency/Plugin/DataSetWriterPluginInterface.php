<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportExtension\Dependency\Plugin;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

/**
 * @deprecated Use `\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface` instead.
 */
interface DataSetWriterPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet);

    /**
     * @api
     *
     * @return void
     */
    public function flush();
}
