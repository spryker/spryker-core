<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer;

use SprykerFeature\Zed\Product\Business\Model\ProductBatchResult;

interface FileImporterInterface
{

    /**
     * @param \SplFileInfo $file
     *
     * @return ProductBatchResult
     */
    public function importFile(\SplFileInfo $file);

}
