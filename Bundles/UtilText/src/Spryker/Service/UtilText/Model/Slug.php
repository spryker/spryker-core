<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model;

class Slug implements SlugInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function generate($value)
    {
        if (function_exists('iconv')) {
            $value = (string)iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = preg_replace('/[^a-zA-Z0-9 -]/', '', trim($value));
        $value = mb_strtolower($value);
        $value = str_replace(' ', '-', $value);
        $value = preg_replace('/(\-)\1+/', '$1', $value);

        return $value;
    }
}
