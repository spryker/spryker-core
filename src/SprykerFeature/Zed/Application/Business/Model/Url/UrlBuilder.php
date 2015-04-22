<?php

namespace SprykerFeature\Zed\Application\Business\Model\Url;

class UrlBuilder implements UrlBuilderInterface
{

    /**
     * @param $bundle
     * @param string $controller
     * @param string $action
     * @param array $queryParameter
     * @return string
     */
    public function build($bundle, $controller = null, $action = null, array $queryParameter = [])
    {
        $hasQueryParameter = (count($queryParameter) > 0);

        $moduleControllerAction = self::fillNullValues($hasQueryParameter, [$bundle, $controller, $action]);
        $moduleControllerAction = self::removeNullValues($moduleControllerAction);

        $url = '/' . implode('/', $moduleControllerAction);

        if ($hasQueryParameter) {
            $url .= '?' . http_build_query($queryParameter);
        }

        return $url;
    }

    /**
     * @param $hasQueryParameter
     * @param $mca
     * @return array
     */
    protected static function fillNullValues($hasQueryParameter, $mca)
    {
        $mapCallback = function ($value) use ($hasQueryParameter) {
            return ($value) ?: (($hasQueryParameter) ? 'index' : null);
        };

        return array_map($mapCallback, $mca);
    }

    /**
     * @param $mca
     * @return array
     */
    protected static function removeNullValues($mca)
    {
        $filterCallback = function ($value) {
            return !is_null($value);
        };

        return array_filter($mca, $filterCallback);
    }
}
