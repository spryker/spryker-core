<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cms\Service\Storage;

interface CmsBlockStorageInterface
{
    /**
     * @param string $blockName
     *
     * @return array
     */
    public function getBlockContent($blockName);

}
