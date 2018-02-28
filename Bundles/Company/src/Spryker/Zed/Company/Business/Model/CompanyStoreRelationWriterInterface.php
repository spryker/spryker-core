<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business\Model;

use Generated\Shared\Transfer\StoreRelationTransfer;

interface CompanyStoreRelationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $storeRelationTransfer
     *
     * @return void
     */
    public function save(StoreRelationTransfer $storeRelationTransfer = null): void;
}
