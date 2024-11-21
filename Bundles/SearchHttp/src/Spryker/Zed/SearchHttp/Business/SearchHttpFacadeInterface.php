<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Business;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;

interface SearchHttpFacadeInterface
{
    /**
     * Specification:
     * - Saves the SearchHttp config using the data from given `SearchHttpConfig` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return void
     */
    public function saveSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void;

    /**
     * Specification:
     * - Deletes the SearchHttp config using the data from given `SearchHttpConfig` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return void
     */
    public function deleteSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void;
}
