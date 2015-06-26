<?php
/**
 * Created by PhpStorm.
 * User: danielsveller
 * Date: 24/06/15
 * Time: 11:34
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;


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
            if ($result['status'] !== 'sent') {
                return false;
            }
        }

        return true;
    }

    /**
     * @return CustomerMailConnectorDependencyContainer
     */
    protected function getDependencyContainer()
    {
        return parent::getDependencyContainer();
    }
}
