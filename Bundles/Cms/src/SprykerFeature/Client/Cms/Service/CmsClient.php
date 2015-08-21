<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Client\Cms\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;

class CmsClient extends AbstractClient implements CmsClientInterface
{
    /**
     * @param string $blockName
     * @param string $localeName
     *
     * @return array
     */
    public function blockFinder($blockName, $localeName)
    {
        return $this->createCmsBlockFinder($localeName)->getBlockContent($blockName);
    }

    /**
     * @param $localeName
     *
     * @return CmsBlockStorageInterface
     */
    private function createCmsBlockFinder($localeName)
    {
        return $this->getDependencyContainer()->createCmsBlockFinder($localeName);
    }
}
