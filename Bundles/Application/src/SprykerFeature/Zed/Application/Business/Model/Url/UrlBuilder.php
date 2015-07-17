<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Url;

class UrlBuilder implements UrlBuilderInterface
{

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param array $queryParameter
     *
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
     * @param bool $hasQueryParameter
     * @param array $mca
     *
     * @return array
     */
    protected static function fillNullValues($hasQueryParameter, array $mca)
    {
        $mapCallback = function ($value) use ($hasQueryParameter) {
            return ($value) ?: (($hasQueryParameter) ? 'index' : null);
        };

        //TODO abbreviation
        return array_map($mapCallback, $mca);
    }

    /**
     * @param array $mca
     *
     * @return array
     */
    protected static function removeNullValues(array $mca)
    {
        $filterCallback = function ($value) {
            return !is_null($value);
        };

        return array_filter($mca, $filterCallback);
    }

}
