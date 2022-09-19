<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Extender;

use Spryker\Zed\Twig\Communication\Filter\FilterFactoryInterface;
use Twig\Environment;

class FilterExtender implements FilterExtenderInterface
{
    /**
     * @var \Spryker\Zed\Twig\Communication\Filter\FilterFactoryInterface
     */
    protected $filterFactory;

    /**
     * @param \Spryker\Zed\Twig\Communication\Filter\FilterFactoryInterface $filterFactory
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
