<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Form\Filter;

use Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;

class SubFormFilter implements SubFormFilterInterface
{
    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected $subFormPlugins;

    /**
     * @var \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    protected $subFormFilterPlugins;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface
     */
    protected $dataContainer;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection|null
     */
    protected $filteredSubFormPlugins = null;

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subFormPlugins
     * @param \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[] $subFormFilterPlugins
     * @param \Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface $dataContainer
     */
    public function __construct(
        SubFormPluginCollection $subFormPlugins,
        array $subFormFilterPlugins,
        DataContainerInterface $dataContainer
    ) {
        $this->subFormPlugins = $subFormPlugins;
        $this->subFormFilterPlugins = $subFormFilterPlugins;
        $this->dataContainer = $dataContainer;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function getSubForms()
    {
        if (!$this->filteredSubFormPlugins) {
            $this->filteredSubFormPlugins = $this->applyFilters();
        }

        return $this->filteredSubFormPlugins;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected function applyFilters()
    {
        $dataTransfer = $this->dataContainer->get();
        $filteredSubFormPlugins = clone $this->subFormPlugins;

        foreach ($this->subFormFilterPlugins as $filter) {
            $filteredSubFormPlugins = $filter->filter($filteredSubFormPlugins, $dataTransfer);
        }

        return $filteredSubFormPlugins;
    }
}
