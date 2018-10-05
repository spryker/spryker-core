<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Graph;

use Spryker\Shared\Graph\Adapter\PhpDocumentorGraphAdapter;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class GraphConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getGraphAdapterName()
    {
        return PhpDocumentorGraphAdapter::class;
    }
}
