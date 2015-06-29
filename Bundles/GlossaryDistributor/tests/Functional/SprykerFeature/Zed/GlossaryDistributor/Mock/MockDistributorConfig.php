<?php

namespace Functional\SprykerFeature\Zed\GlossaryDistributor\Mock;

use SprykerFeature\Zed\Distributor\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\Distributor\DistributorConfig;

class MockDistributorConfig extends DistributorConfig
{

    /**
     * @return QueryExpanderPluginInterface[]
     */
    public function getQueryExpander()
    {
        return [
            $this->getLocator()->glossaryDistributor()->pluginGlossaryQueryExpanderPlugin(),
        ];
    }
}
