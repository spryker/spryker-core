<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Configuration;
use Spryker\Zed\Testify\Locator\Business\BusinessLocator;

class LocatorHelper extends ConfigHelper
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
