<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Search;

interface CategoryNodePageSearchListenerInterface
{

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds);

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds);
}
