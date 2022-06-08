<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\Expander;

use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface;

interface ContextExpanderCollectionInterface
{
    /**
     * @param array<string> $apiApplications
     *
     * @return $this
     */
    public function addApplications(array $apiApplications = []);

    /**
     * @param \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface $contextExpanderPlugin
     * @param array<string> $apiApplications
     *
     * @return $this
     */
    public function addExpander(ContextExpanderPluginInterface $contextExpanderPlugin, array $apiApplications = []);

    /**
     * @param string $apiApplication
     *
     * @return array<int, \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface>
     */
    public function getExpanders(string $apiApplication): array;
}
