<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Dependency\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Ui\Communication\Plugin\Form\StateContainer\StateContainerInterface;
use SprykerFeature\Zed\Ui\Communication\UiDependencyContainer;

abstract class AbstractFormPlugin extends AbstractPlugin implements FormPluginInterface
{

    const OUTPUT_TYPE = 'type';
    const OUTPUT_NAME = 'name';

    /**
     * @var UiDependencyContainer
     */
    protected $dependencyContainer;

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
