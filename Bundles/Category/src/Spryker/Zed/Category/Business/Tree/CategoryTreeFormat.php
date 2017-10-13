<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

/**
 * @deprecated Will be removed with next major release
 */
class CategoryTreeFormat
{
    /**
     * @param array $categories
     * @param int $parentIdCategory
     *
     * @return array
     */
    public static function formatForJsTreePlugin(array $categories, $parentIdCategory)
    {
        $children = [];
        $children['state'] = [
            'opened' => true,
        ];

        foreach ($categories as $item) {
            if ($item['parent'] === '#') {
                $children['text'] = $item['text'];
                $children['state']['selected'] = ((int)$parentIdCategory === (int)$item['id']);
            } else {
                $children['children'][] = $item['text'];
            }
        }

        return $children;
    }
}
