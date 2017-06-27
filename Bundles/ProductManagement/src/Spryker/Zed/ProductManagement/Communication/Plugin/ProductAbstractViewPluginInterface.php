<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Plugin;

interface ProductAbstractViewPluginInterface
{

    /**
     * Specification
     * - get a subject name of the plugin
     *
     * @api
     *
     * @return string
     */
    public function getName();

    /**
     * Specification
     * - get a list of rendered subject statements
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return string[]
     */
    public function getRenderedList($idProductAbstract);

}
