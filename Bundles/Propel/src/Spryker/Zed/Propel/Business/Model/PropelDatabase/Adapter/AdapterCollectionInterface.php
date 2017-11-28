<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter;

interface AdapterCollectionInterface
{
    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterInterface $adapter
     *
     * @return $this
     */
    public function addAdapter(AdapterInterface $adapter);

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterInterface
     */
    public function getAdapter();

    /**
     * @param string $adapter
     *
     * @return bool
     */
    public function hasAdapter($adapter);
}
