<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

interface DataImporterBeforeImportAwareInterface extends DataImporterBeforeImportInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface $beforeImportHook
     *
     * @return $this
     */
    public function addBeforeImportHook(DataImporterBeforeImportInterface $beforeImportHook);
}
