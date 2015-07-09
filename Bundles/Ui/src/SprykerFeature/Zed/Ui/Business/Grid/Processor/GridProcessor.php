<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Business\Grid\Processor;

use Propel\Runtime\Propel;
use SprykerFeature\Zed\Ui\Business\Grid\StateContainer\Bean\StateContainerBeanInterface;
use SprykerFeature\Zed\Ui\Business\Grid\StateContainer\Bean\StateContainerBeanTrait;
use SprykerFeature\Zed\Ui\Business\Grid\StateContainer\StateContainerInterface;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\Ui\Dependency\Plugin\GridPluginInterface;

class GridProcessor implements GridProcessorInterface, StateContainerBeanInterface
{

    use StateContainerBeanTrait;

    /**
     * @var GridPluginInterface[]
     */
    protected $plugins;

    /**
     * @param array $plugins
     * @param StateContainerInterface $stateContainer
     */
    public function __construct(array $plugins, StateContainerInterface $stateContainer)
    {
        $this->plugins = $plugins;

        $this->setStateContainer($stateContainer);

        $this->injectStateContainer();
        $this->injectSpecifiedQuery();
        $this->injectTrimmedQuery();
        $this->injectQueryResult();
    }

    protected function injectStateContainer()
    {
        foreach ($this->plugins as $plugin) {
            $plugin->setStateContainer($this->getStateContainer());
        }
    }

    protected function injectSpecifiedQuery()
    {
        $this->getStateContainer()->setSpecifiedQuery(
            $this->specifyQuery(
                clone $this->getStateContainer()->getBaseQuery()
            )
        );
    }

    /**
     * @param ModelCriteria $query
     *
     * @return mixed|ModelCriteria
     */
    protected function specifyQuery(ModelCriteria $query)
    {
        foreach ($this->plugins as $plugin) {
            $query = $plugin->specifyQuery($query);
        }

        return $query;
    }

    protected function injectTrimmedQuery()
    {
        $this->getStateContainer()->setTerminatedQuery(
            $this->terminateQuery(
                clone $this->getStateContainer()->getSpecifiedQuery()
            )
        );
    }

    /**
     * @param ModelCriteria $query
     *
     * @return mixed|ModelCriteria
     */
    protected function terminateQuery(ModelCriteria $query)
    {
        foreach ($this->plugins as $plugin) {
            $query = $plugin->terminateQuery($query);
        }

        return $query;
    }

    protected function injectQueryResult()
    {
        Propel::disableInstancePooling();
        $this->getStateContainer()->setQueryResult(
            $this->getStateContainer()->getTerminatedQuery()->find()
        );
        Propel::enableInstancePooling();
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [];
        foreach ($this->plugins as $plugin) {
            $data = $plugin->getData($data);
        }

        return $data;
    }

}
