<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Spryker\Zed\Testify\Locator\Business\BusinessLocator;

class Locator extends Module
{

    /**
     * @var array
     */
    protected $config = [
        'projectNamespaces' => [],
        'coreNamespaces' => [
            'Spryker',
        ],
    ];

    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface|\Generated\Zed\Ide\AutoCompletion|\Generated\Service\Ide\AutoCompletion
     */
    public function getLocator()
    {
        return new BusinessLocator($this->config['projectNamespaces'], $this->config['coreNamespaces']);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade()
    {
        $currentNamespace = Configuration::config()['namespace'];
        $bundleName = lcfirst($currentNamespace);

        return $this->getLocator()->$bundleName()->facade();
    }

}
