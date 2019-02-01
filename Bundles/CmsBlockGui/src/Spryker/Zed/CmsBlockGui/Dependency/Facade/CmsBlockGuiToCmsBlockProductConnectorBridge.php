<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Dependency\Facade;

class CmsBlockGuiToCmsBlockProductConnectorBridge implements CmsBlockGuiToCmsBlockProductConnectorInterface
{
    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface
     */
    protected $cmsBlockFacadeProductConnector;

    /**
     * @param \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface $cmsBlockFacadeProductConnector
     */
    public function __construct($cmsBlockFacadeProductConnector)
    {
        $this->cmsBlockFacadeProductConnector = $cmsBlockFacadeProductConnector;
    }

    /**
     * @param string $suggestion
     *
     * @return array
     */
    public function suggestProductAbstract(string $suggestion): array
    {
        return $this->cmsBlockFacadeProductConnector->suggestProductAbstract($suggestion);
    }
}
