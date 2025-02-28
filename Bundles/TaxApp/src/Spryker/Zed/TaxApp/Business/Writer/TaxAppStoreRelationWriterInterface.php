<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Writer;

interface TaxAppStoreRelationWriterInterface
{
    /**
     * @return void
     */
    public function refreshTaxAppStoreRelations(): void;
}
