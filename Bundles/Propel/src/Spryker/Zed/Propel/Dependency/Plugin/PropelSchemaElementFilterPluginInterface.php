<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Dependency\Plugin;

use SimpleXMLElement;

interface PropelSchemaElementFilterPluginInterface
{
    /**
     * Specification:
     * - Filters Propel's schema elements.
     *
     * @api
     *
     * @param \SimpleXMLElement $schemaXmlElement
     *
     * @return \SimpleXMLElement
     */
    public function filter(SimpleXMLElement $schemaXmlElement): SimpleXMLElement;
}
