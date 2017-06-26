<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Plugin;

interface CmsContentWidgetParameterMapperPluginInterface
{

    /**
     * Specification:
     *  Cms content widget parameter plugins is used when collecting data to yves data store,
     *  this mapping is needed because parameters provider to functions is not the same as we use to read from yves data store.
     *  For example 'sku1' => 'primary key in redis', this will map sku to primary key and store together with cms content.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return array
     */
    public function map(array $parameters);

}
