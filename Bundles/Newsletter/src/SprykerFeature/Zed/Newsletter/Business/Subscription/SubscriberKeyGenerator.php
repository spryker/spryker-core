<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

class SubscriberKeyGenerator implements SubscriberKeyGeneratorInterface
{
    /**
     * @return string
     */
    public function generateKey()
    {
        return uniqid();
    }
}
