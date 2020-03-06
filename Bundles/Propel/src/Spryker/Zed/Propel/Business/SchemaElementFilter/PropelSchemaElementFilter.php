<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\SchemaElementFilter;

use SimpleXMLElement;

class PropelSchemaElementFilter implements SchemaElementFilterInterface
{
    /**
     * @var \Spryker\Zed\Propel\Dependency\Plugin\PropelSchemaElementFilterPluginInterface[]
     */
    protected $schemaElementFilterPlugins;

    /**
     * @param \Spryker\Zed\Propel\Dependency\Plugin\PropelSchemaElementFilterPluginInterface[] $schemaElementFilterPlugins
     */
    public function __construct(array $schemaElementFilterPlugins)
    {
        $this->schemaElementFilterPlugins = $schemaElementFilterPlugins;
    }

    /**
     * @param \SimpleXMLElement $schemaXmlElement
     *
     * @return \SimpleXMLElement
     */
    public function filter(SimpleXMLElement $schemaXmlElement): SimpleXMLElement
    {
        foreach ($this->schemaElementFilterPlugins as $elementFilterPlugin) {
            $schemaXmlElement = $elementFilterPlugin->filter($schemaXmlElement);
        }

        return $schemaXmlElement;
    }
}
