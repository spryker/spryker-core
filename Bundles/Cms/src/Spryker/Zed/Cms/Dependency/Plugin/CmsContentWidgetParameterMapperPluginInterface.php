<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Plugin;

interface CmsContentWidgetParameterMapperPluginInterface
{

    /**
     * @api
     *
     * @param array $parameters
     *
     * @return array
     */
    public function map(array $parameters);

}
