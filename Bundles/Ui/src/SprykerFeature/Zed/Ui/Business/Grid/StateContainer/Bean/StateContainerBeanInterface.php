<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Business\Grid\StateContainer\Bean;

use SprykerFeature\Zed\Ui\Business\Grid\StateContainer\StateContainerInterface;

interface StateContainerBeanInterface
{

    /**
     * @return StateContainerInterface
     */
    public function getStateContainer();

    /**
     * @param StateContainerInterface $stateContainer
     */
    public function setStateContainer(StateContainerInterface $stateContainer);

}
