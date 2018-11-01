<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace {vendor}\{application}\{module}\Dependency\{toType};
{useStatements}

class {module}To{toModule}{toType}Bridge implements {module}To{toModule}{toType}Interface
{
    /**
     * @var \{toVendor}\{toApplication}\{toModule}{toModuleLayer}\{toModule}{toType}Interface
     */
    protected ${toModuleVariable}{toType};

    /**
     * @param \{toVendor}\{toApplication}\{toModule}{toModuleLayer}\{toModule}{toType}Interface ${toModuleVariable}{toType}
     */
    public function __construct(${toModuleVariable}{toType})
    {
        $this->{toModuleVariable}{toType} = ${toModuleVariable}{toType};
    }

    {methods}
}
