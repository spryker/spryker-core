<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Filter;

use Twig\Environment;
use Twig\TwigFilter;

class FilterFactory implements FilterFactoryInterface
{
    /**
     * @var string
     */
    protected const EXECUTE_FILTER_IF_EXISTS_FILTER_NAME = 'executeFilterIfExists';

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFilter
     */
    public function createExecuteFilterIfExistsFilter(Environment $twig): TwigFilter
    {
        return new TwigFilter(
            static::EXECUTE_FILTER_IF_EXISTS_FILTER_NAME,
            function ($filterInput, string $filterName, ...$filterArguments) use ($twig) {
                $filter = $twig->getFilter($filterName);
                if (!$filter) {
                    return $filterInput;
                }

                return $filter->getCallable()($filterInput, ...$filterArguments);
            },
        );
    }
}
