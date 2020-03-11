<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\SchemaElementFilter;

use SimpleXMLElement;

interface SchemaElementFilterInterface
{
    /**
     * @param \SimpleXMLElement $schemaXmlElements
     *
     * @return \SimpleXMLElement
     */
    public function filter(SimpleXMLElement $schemaXmlElements): SimpleXMLElement;
}
