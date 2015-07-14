<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Assets\Communication\Model;

interface MediaUrlBuilderInterface
{

    /**
     * @param string $mediaPath
     *
     * @throws \Exception
     *
     * @return string
     */
    public function buildUrl($mediaPath);

}
