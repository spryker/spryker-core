<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\KeyBuilder;

class ChannelKeyBuilder implements ChannelKeyBuilderInterface
{

    /**
     * @param $type
     *
     * @return string
     */
    public function buildChannelKey($type)
    {
        return $type;
    }
}
