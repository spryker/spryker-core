<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Generator;

class UrlPathGenerator implements UrlPathGeneratorInterface
{
    /**
     * @uses \Spryker\Zed\Category\Persistence\CategoryRepository::KEY_NAME
     */
    public const CATEGORY_NAME = 'name';

    /**
     * @param array $categoryPath
     *
     * @return string
     */
    public function generate(array $categoryPath): string
    {
        $formattedPath = [];

        foreach ($categoryPath as $category) {
            $categoryName = trim($category[static::CATEGORY_NAME]);

            if ($categoryName !== '') {
                $formattedPath[] = mb_strtolower(str_replace(' ', '-', $categoryName));
            }
        }

        return '/' . implode('/', $formattedPath);
    }
}
