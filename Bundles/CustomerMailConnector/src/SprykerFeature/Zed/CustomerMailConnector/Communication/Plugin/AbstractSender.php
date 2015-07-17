<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class AbstractSender extends AbstractPlugin
{

    /**
     * @param array $results
     *
     * @return bool
     */
    protected function isMailSent(array $results)
    {
        foreach ($results as $result) {
            if (!isset($result['status'])) {
                return false;
            }
            if ($result['status'] !== 'sent' || $result['status'] !== 'queued') {
                return false;
            }
        }

        return true;
    }

}
