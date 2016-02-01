<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer;

interface FileImporterInterface
{

    /**
     * @param \SplFileInfo $file
     *
     * @return \Spryker\Zed\Product\Business\Model\ProductBatchResult
     */
    public function importFile(\SplFileInfo $file);

}
