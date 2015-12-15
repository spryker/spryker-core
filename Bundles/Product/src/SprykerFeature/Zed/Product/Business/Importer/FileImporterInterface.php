<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer;

use Spryker\Zed\Product\Business\Model\ProductBatchResult;

interface FileImporterInterface
{

    /**
     * @param \SplFileInfo $file
     *
     * @return ProductBatchResult
     */
    public function importFile(\SplFileInfo $file);

}
