<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner;

interface IndexMapCleanerInterface
{
    /**
     * @return void
     */
    public function cleanDirectory(): void;
}
