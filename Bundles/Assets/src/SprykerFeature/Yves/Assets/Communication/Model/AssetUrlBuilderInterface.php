<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Assets\Communication\Model;

interface AssetUrlBuilderInterface
{

    /**
     * @param string $assetPath
     *
     * @throws \Exception
     *
     * @return string
     */
    public function buildUrl($assetPath);

}
