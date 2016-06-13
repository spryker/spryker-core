<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Dependency\Plugin;

interface CollectorPluginCollectionInterface
{

    /**
     * @param \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface $collectorPlugin
     * @param string $type
     *
     * @return $this
     */
    public function addPlugin(CollectorPluginInterface $collectorPlugin, $type);

    /**
     * @param string $type
     *
     * @return CollectorPluginInterface
     */
    public function getPlugin($type);

    /**
     * @param string $type
     *
     * @return CollectorPluginInterface
     */
    public function hasPlugin($type);

    /**
     * @return array
     */
    public function getTypes();

}
