<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Resource;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class GenericResource implements ResourceInterface
{
    /**
     * @var callable
     */
    protected $executable;

    /**
     * @param callable $executable
     */
    public function __construct(callable $executable)
    {
        $this->executable = $executable;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return callable
     */
    public function getResource(GlueRequestTransfer $glueRequestTransfer): callable
    {
        return $this->executable;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return '';
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return new GlueResourceMethodCollectionTransfer();
    }
}
