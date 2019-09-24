<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilSanitize\UtilSanitizeServiceFactory getFactory()
 */
class UtilSanitizeService extends AbstractService implements UtilSanitizeServiceInterface
{
    /**
     * Specification:
     *  - Escapes any string for safe output in HTML.
     *
     * @api
     *
     * @param string $text
     * @param bool $double
     * @param string|null $charset
     *
     * @return string
     */
    public function escapeHtml($text, $double = true, $charset = null)
    {
        return $this->getFactory()
            ->createHtml()
            ->escape($text, $double, $charset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array)
    {
        return $this->getFactory()->createArrayFilter()->arrayFilterRecursive($array);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $array
     *
     * @return array
     */
    public function filterOutBlankValuesRecursively(array $array): array
    {
        return $this->getFactory()
            ->createArrayFilter()
            ->filterOutBlankValuesRecursively($array);
    }
}
