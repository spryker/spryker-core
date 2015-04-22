<?php

namespace SprykerFeature\Zed\Ui\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\UiBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method UiBusiness getFactory()
 */
class UiDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @param array $plugins
     * @param array $requestData
     * @param ModelCriteria $query
     * @return object
     */
    public function getGridProcessor(array $plugins, array $requestData, ModelCriteria $query)
    {
        $gridProcessor = $this->getFactory()->createGridProcessorGridProcessor(
            $plugins,
            $this->getStateContainer($requestData, $query)
        );

        return $gridProcessor;
    }

    /**
     * @param array $requestData
     * @param ModelCriteria $query
     * @return Grid\StateContainer\StateContainer
     */
    public function getStateContainer(array $requestData, ModelCriteria $query)
    {
        $stateContainer = $this->getFactory()->createGridStateContainerStateContainer(
            $requestData,
            $query
        );

        return $stateContainer;
    }
}
