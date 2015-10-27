<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

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
                $children['state']['selected'] = ((int) $parentIdCategory === (int) $item['id']);
            } else {
                $children['children'][] = $item['text'];
            }
        }

        return $children;
    }

}
