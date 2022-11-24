<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Extender;

use Spryker\Shared\Twig\Filter\FilterFactoryInterface;
use Twig\Environment;

class FilterExtender implements FilterExtenderInterface
{
    /**
     * @var \Spryker\Shared\Twig\Filter\FilterFactoryInterface
     */
    protected FilterFactoryInterface $filterFactory;

    /**
     * @param \Spryker\Shared\Twig\Filter\FilterFactoryInterface $filterFactory
     */
    public function __construct(FilterFactoryInterface $filterFactory)
    {
        $this->filterFactory = $filterFactory;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig): Environment
    {
        $twig->addFilter($this->filterFactory->createExecuteFilterIfExistsFilter($twig));

        return $twig;
    }
}
