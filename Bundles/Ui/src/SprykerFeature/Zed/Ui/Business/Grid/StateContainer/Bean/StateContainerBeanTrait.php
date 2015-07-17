<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Business\Grid\StateContainer\Bean;

use SprykerFeature\Zed\Ui\Business\Grid\StateContainer\StateContainerInterface;

trait StateContainerBeanTrait
{

    /**
     * @var StateContainerInterface
     */
    protected $stateContainer;

    /**
     * @return StateContainerInterface
     */
    public function getStateContainer()
    {
        return $this->stateContainer;
    }

    /**
     * @param StateContainerInterface $stateContainer
     */
    public function setStateContainer(StateContainerInterface $stateContainer)
    {
        $this->stateContainer = $stateContainer;
    }

}
