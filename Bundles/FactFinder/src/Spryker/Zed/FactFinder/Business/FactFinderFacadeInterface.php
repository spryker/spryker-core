<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Generated\Shared\Transfer\LocaleTransfer;

interface FactFinderFacadeInterface
{

    /**
     * Specification:
     * - Creates a csv file
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransferTransfer
     *
     * @return mixed
     */
    public function createFactFinderCsv(LocaleTransfer $localeTransferTransfer);

}
