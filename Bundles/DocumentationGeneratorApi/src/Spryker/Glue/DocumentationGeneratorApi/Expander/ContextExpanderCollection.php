<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\Expander;

use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface;

class ContextExpanderCollection implements ContextExpanderCollectionInterface
{
    /**
     * Key is the API application the expander will be running for.
     *
     * @var array<string, array<int, \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface>>
     */
    protected array $expanders = [];

    /**
     * @param array<string> $apiApplications
     *
     * @return $this
     */
    public function addApplications(array $apiApplications = [])
    {
        foreach ($apiApplications as $apiApplication) {
            if (!array_key_exists($apiApplication, $this->expanders)) {
                $this->expanders[$apiApplication] = [];
            }
        }

        return $this;
    }

    /**
     * @param \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface $contextExpanderPlugin
     * @param array<string> $apiApplications
     *
     * @return $this
     */
    public function addExpander(ContextExpanderPluginInterface $contextExpanderPlugin, array $apiApplications = [])
    {
        if (!$apiApplications) {
            $apiApplications = array_keys($this->expanders);
        }

        foreach ($this->expanders as $apiApplication => $expanders) {
            if (!in_array($apiApplication, $apiApplications)) {
                continue;
            }

            if (in_array($contextExpanderPlugin, $expanders)) {
                return $this;
            }

            $this->expanders[$apiApplication][] = $contextExpanderPlugin;
        }

        return $this;
    }

    /**
     * @param string $apiApplication
     *
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface>
     */
    public function getExpanders(string $apiApplication): array
    {
        return $this->expanders[$apiApplication] ?? [];
    }
}
