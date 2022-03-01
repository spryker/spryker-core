<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;

/**
 * Base interface for the all API resources. This interface can be extended by the conventions.
 */
interface ResourceInterface
{
    /**
     * Specification:
     * - Return a callable that will return a response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return callable():\Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getResource(GlueRequestTransfer $glueRequestTransfer): callable;

    /**
     * Specification:
     * - Returns the unique resource name.
     *
     * @api
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Specification:
     * - Returns the default controller FQCN.
     *
     * @api
     *
     * @return string
     */
    public function getController(): string;

    /**
     * Specification:
     * - Returns methods configuration for the resource.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer;
}
