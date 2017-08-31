<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Form\Provider;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;

class FilterableSubFormProvider
{

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected $subFormPlugins;

    /**
     * @var \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    protected $subFormFilters;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection|null
     */
    protected $filteredSubFormPlugins = null;

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subFormPlugins
     * @param \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[] $subFormFilters
     */
    public function __construct(SubFormPluginCollection $subFormPlugins, array $subFormFilters)
    {
        $this->subFormPlugins = $subFormPlugins;
        $this->subFormFilters = $subFormFilters;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function getSubForms(AbstractTransfer $dataTransfer)
    {
        if (!$this->filteredSubFormPlugins) {
            $this->filteredSubFormPlugins = $this->applyFilters($dataTransfer);
        }

        return $this->filteredSubFormPlugins;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected function applyFilters(AbstractTransfer $data)
    {
        $filteredSubFormPlugins = $this->subFormPlugins;
        foreach ($this->subFormFilters as $filter) {
            $filteredSubFormPlugins = $filter->filter($filteredSubFormPlugins, $data);
        }

        return $filteredSubFormPlugins;
    }

}
