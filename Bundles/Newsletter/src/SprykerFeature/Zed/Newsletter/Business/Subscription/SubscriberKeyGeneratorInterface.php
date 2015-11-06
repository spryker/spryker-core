<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

interface SubscriberKeyGeneratorInterface
{

    /**
     * @return string
     */
    public function generateKey();

}
