<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

interface DataSetImporterAwareInterface
{

    /**
     * Specification:
     * - Adds a DataSetImporterInterface.
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetImporterInterface $dataSetHandler
     *
     * @return $this
     */
    public function addDataSetImporter(DataSetImporterInterface $dataSetHandler);

}
