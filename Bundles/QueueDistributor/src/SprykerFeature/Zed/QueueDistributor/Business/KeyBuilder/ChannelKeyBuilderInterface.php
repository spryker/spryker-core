<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\KeyBuilder;

interface ChannelKeyBuilderInterface
{
    /**
     * @param $type
     */
    public function buildChannelKey($type);
}
