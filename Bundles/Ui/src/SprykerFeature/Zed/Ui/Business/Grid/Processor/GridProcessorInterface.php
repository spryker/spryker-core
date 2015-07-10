<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Business\Grid\Processor;

use SprykerFeature\Zed\Ui\Business\Grid\StateContainer\StateContainerInterface;

interface GridProcessorInterface
{

    /**
     * @param array $plugins
     * @param StateContainerInterface $stateContainer
     */
    public function __construct(array $plugins, StateContainerInterface $stateContainer);

    /**
     * @return array
     */
    public function getData();

}
