<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cms\Service;

interface CmsClientInterface
{
    /**
     * @param string $blockName
     * @param string $localeName
     *
     * @return array
     */
    public function blockFinder($blockName, $localeName);

}
