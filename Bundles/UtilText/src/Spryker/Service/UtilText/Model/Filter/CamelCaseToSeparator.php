<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model\Filter;

class CamelCaseToSeparator implements CamelCaseToSeparatorInterface
{

    /**
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public function filter($string, $separator = '-')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . addcslashes($separator, '$') . '$2', $string));
    }

}
