<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;

class ConfigurableBundlePageSearchToConfigurableBundleFacadeBridge implements ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface
     */
    protected $configurableBundleFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface $configurableBundleFacade
     */
    public function __construct($configurableBundleFacade)
    {
        $this->configurableBundleFacade = $configurableBundleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[]
     */
    public function getConfigurableBundleTemplateCollection(ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer): array
    {
        return $this->configurableBundleFacade->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer);
    }
}
