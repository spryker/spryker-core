<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Business\Model;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;

interface ConfigDeleterInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\SearchHttp\Business\Model\ConfigDeleterInterface::deleteSearchHttpConfig()} instead.
     *
     * @param string $storeReference
     * @param string $applicationId
     *
     * @return void
     */
    public function delete(string $storeReference, string $applicationId): void;

    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return void
     */
    public function deleteSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void;
}
