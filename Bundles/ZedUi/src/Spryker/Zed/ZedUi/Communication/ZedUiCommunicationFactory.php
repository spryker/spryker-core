<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ZedUi\Communication\Twig\NavigationComponentConfigFunction;
use Spryker\Zed\ZedUi\Dependency\Service\ZedUiToUtilEncodingServiceInterface;
use Spryker\Zed\ZedUi\ZedUiDependencyProvider;

class ZedUiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ZedUi\Communication\Twig\NavigationComponentConfigFunction
     */
    public function createNavigationComponentConfigFunction(): NavigationComponentConfigFunction
    {
        return new NavigationComponentConfigFunction($this->getUtilEncoding());
    }

    /**
     * @return \Spryker\Zed\ZedUi\Dependency\Service\ZedUiToUtilEncodingServiceInterface
     */
    public function getUtilEncoding(): ZedUiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ZedUiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
