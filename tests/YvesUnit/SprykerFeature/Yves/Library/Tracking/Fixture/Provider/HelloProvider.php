<?php

namespace YvesUnit\SprykerFeature\Yves\Library\Tracking\Fixture\Provider;

use SprykerFeature\Yves\Library\Tracking\PageTypeInterface;
use SprykerFeature\Yves\Library\Tracking\Provider\ProviderInterface;
use SprykerFeature\Yves\Library\Tracking\Tracking;

class HelloProvider implements ProviderInterface, PageTypeInterface
{

    /**
     * @param array $dataProvider
     * @param $pageType
     * @return mixed|string
     */
    public function getTrackingOutput(array $dataProvider, $pageType)
    {
        if ($pageType === 'home') {
            return 'Hello';
        } else {
            return 'Hallo';
        }
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return Tracking::POSITION_BEFORE_CLOSING_HEAD;
    }
}
