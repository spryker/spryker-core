<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Assets\Model;

interface AssetUrlBuilderInterface
{
    /**
     * @param string $assetPath
     *
     * @return string
     * @throws \Exception
     */
    public function buildUrl($assetPath);
}