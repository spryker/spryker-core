<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Model;

interface RequestModelFactoryInterface
{

    /**
     * @param string $modelType
     * @param callable $builder
     *
     * @return $this
     */
    public function registerBuilder($modelType, $builder);

    /**
     * @param string $modelType
     *
     * @return \Spryker\Zed\FactFinder\Business\Api\Model\RequestInterface
     */
    public function build($modelType);

}
