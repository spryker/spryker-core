<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business\Exploder;

interface GroupedNodeExploderInterface
{

    /**
     * @param array $data
     * @param string $idsField
     * @param string $namesField
     * @param string $urlField
     *
     * @return array
     */
    public function explodeGroupedNodes(array $data, $idsField, $namesField, $urlField);

}
