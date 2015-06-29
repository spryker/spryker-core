<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Assets\Model;

interface MediaUrlBuilderInterface
{
    /**
     * @param string $mediaPath
     *
     * @return string
     * @throws \Exception
     */
    public function buildUrl($mediaPath);
}