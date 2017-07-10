<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

interface FacetSearchResultValueTransformerPluginInterface
{

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformForDisplay($value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformFromDisplay($value);

}
