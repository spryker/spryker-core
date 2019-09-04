<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface FacetSearchResultValueTransformerPluginInterface
{
    /**
     * @api
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformForDisplay($value);

    /**
     * @api
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformFromDisplay($value);
}
