<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Url\Builder;

class UrlBuilder implements UrlBuilderInterface
{

    /**
     * @param string $bundle
     * @param string|null $controller
     * @param string|null $action
     * @param array $queryParameter
     *
     * @return string
     */
    public function build($bundle, $controller = null, $action = null, array $queryParameter = [])
    {
        $hasQueryParameter = (count($queryParameter) > 0);

        $moduleControllerAction = $this->fillNullValues($hasQueryParameter, [$bundle, $controller, $action]);
        $moduleControllerAction = $this->removeNullValues($moduleControllerAction);

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
    protected function fillNullValues($hasQueryParameter, array $mca)
    {
        $mapCallback = function ($value) use ($hasQueryParameter) {
            return ($value) ?: (($hasQueryParameter) ? 'index' : null);
        };

        return array_map($mapCallback, $mca);
    }

    /**
     * @param array $mca
     *
     * @return array
     */
    protected function removeNullValues(array $mca)
    {
        $filterCallback = function ($value) {
            return $value !== null;
        };

        return array_filter($mca, $filterCallback);
    }

}
