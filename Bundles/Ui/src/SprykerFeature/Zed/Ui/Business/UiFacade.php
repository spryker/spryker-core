<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method UiDependencyContainer getDependencyContainer()
 */
class UiFacade extends AbstractFacade
{

    /**
     * @param array $plugins
     * @param array $requestData
     * @param ModelCriteria $query
     *
     * @return mixed
     */
    public function getGridOutput(array $plugins, array $requestData, ModelCriteria $query)
    {
        $gridProcessor = $this->getDependencyContainer()->getGridProcessor($plugins, $requestData, $query);

        return $gridProcessor->getData();
    }

}
