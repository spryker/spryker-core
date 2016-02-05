<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Graph;

use Spryker\Shared\Graph\Adapter\PhpDocumentorGraphAdapter;
use Spryker\Shared\Graph\GraphAdapterInterface;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class GraphConfig extends AbstractBundleConfig
{

    /**
     * @return GraphAdapterInterface
     */
    public function getGraphAdapterName()
    {
        return PhpDocumentorGraphAdapter::class;
    }

}
