<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerFeature\Yves\Library\Tracking\Fixture\Provider;

use SprykerFeature\Yves\Library\Tracking\PageTypeInterface;
use SprykerFeature\Yves\Library\Tracking\Provider\ProviderInterface;
use SprykerFeature\Yves\Library\Tracking\Tracking;

class FooProvider implements ProviderInterface, PageTypeInterface
{

    /**
     * @param array $dataProvider
     * @param $pageType
     *
     * @return string
     */
    public function getTrackingOutput(array $dataProvider, $pageType)
    {
        if ($pageType === self::PAGE_TYPE_HOME) {
            return 'Foo';
        }
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return Tracking::POSITION_AFTER_OPENING_BODY;
    }

}
