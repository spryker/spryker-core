<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Extender;

use Spryker\Shared\Twig\Extension\EnvironmentCoreExtensionInterface;
use Spryker\Shared\Twig\Filter\FilterFactoryInterface;
use Twig\Environment;

class FilterExtender implements FilterExtenderInterface
{
    /**
     * @param \Spryker\Shared\Twig\Filter\FilterFactoryInterface $filterFactory
     * @param \Spryker\Shared\Twig\Extension\EnvironmentCoreExtensionInterface $environmentCoreExtension
     */
    public function __construct(protected FilterFactoryInterface $filterFactory, protected EnvironmentCoreExtensionInterface $environmentCoreExtension)
    {
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig): Environment
    {
        $this->environmentCoreExtension->extend($twig);
        $twig->addFilter($this->filterFactory->createExecuteFilterIfExistsFilter($twig));

        return $twig;
    }
}
