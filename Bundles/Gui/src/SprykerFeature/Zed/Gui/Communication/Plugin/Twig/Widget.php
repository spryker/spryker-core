<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class Widget extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'widget';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($path, array $params = [], $id = null) {
            if ($id === null) {
                $id = $this->createId($path);
            }
            $url = $this->buildUrl($path, $params);

            return $this->render($url, $id);
        };
    }

    /**
     * @param $url
     * @param $id
     *
     * @return string
     */
    protected function render($url, $id)
    {
        return <<<HTML
<div class="placeholder" data-replace-with="{$url}" data-identifier="{$id}">
  <div class="alert alert-warning">
    loading widget: failed... <a class="widget-retry alert-link" href="{$url}">retry</a>
  </div>
</div>
HTML;
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function createId($path)
    {
        $filter = new \Zend_Filter_Alpha();
        $id = $filter->filter($path);

        return $id;
    }

    /**
     * @param $path
     * @param array $params
     *
     * @return string
     */
    protected function buildUrl($path, array $params = [])
    {
        if (strpos($path, '/') === 0) {
            $path = ltrim($path, '/');

            $pathArray = explode('/', $path);
            for ($i = 0; $i < 3; $i++) {
                if (!isset($pathArray[$i])) {
                    $pathArray[$i] = 'index';
                }
            }

            $url = '/' . implode('/', $pathArray);
            if (count($params) > 0) {
                $url .= '?' . http_build_query($params);
            }
        } else {
            $url = $path;
        }

        return $url;
    }

}
