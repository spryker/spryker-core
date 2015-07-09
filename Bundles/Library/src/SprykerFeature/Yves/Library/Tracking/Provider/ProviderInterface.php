<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Library\Tracking\Provider;

interface ProviderInterface
{

    /**
     * @param array  $dataProvider
     * @param string $pageType
     *
     * @return mixed
     */
    public function getTrackingOutput(array $dataProvider, $pageType);

    /**
     * @return string
     */
    public function getPosition();

}
