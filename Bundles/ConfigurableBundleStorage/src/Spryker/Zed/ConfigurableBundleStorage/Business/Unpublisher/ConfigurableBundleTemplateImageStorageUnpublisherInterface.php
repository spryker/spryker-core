<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher;

interface ConfigurableBundleTemplateImageStorageUnpublisherInterface
{
    /**
     * @param array<int> $configurableBundleTemplateIds
     *
     * @return void
     */
    public function unpublish(array $configurableBundleTemplateIds): void;
}
