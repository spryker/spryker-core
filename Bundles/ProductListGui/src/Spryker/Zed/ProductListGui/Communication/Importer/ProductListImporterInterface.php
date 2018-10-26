<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Importer;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ProductListImporterInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $productsConcreteCsv
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productConcreteRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    public function importFromCsvFile(UploadedFile $productsConcreteCsv, ProductListProductConcreteRelationTransfer $productConcreteRelationTransfer): ProductListProductConcreteRelationTransfer;
}
