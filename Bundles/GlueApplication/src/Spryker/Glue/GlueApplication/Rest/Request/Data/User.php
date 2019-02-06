<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

use Generated\Shared\Transfer\RestUserIdentifierTransfer;

class User implements UserInterface
{
    /**
     * @var string
     */
    protected $surrogateIdentifier;

    /**
     * @var string
     */
    protected $naturalIdentifier;

    /**
     * @var array
     */
    protected $scopes;

    /**
     * @var \Generated\Shared\Transfer\RestUserIdentifierTransfer|null
     */
    protected $restUserIdentifierTransfer;

    /**
     * @param string $surrogateIdentifier
     * @param string $naturalIdentifier
     * @param array $scopes
     * @param \Generated\Shared\Transfer\RestUserIdentifierTransfer|null $restUserIdentifierTransfer
     */
    public function __construct(string $surrogateIdentifier, string $naturalIdentifier, array $scopes = [], ?RestUserIdentifierTransfer $restUserIdentifierTransfer = null)
    {
        $this->surrogateIdentifier = $surrogateIdentifier;
        $this->naturalIdentifier = $naturalIdentifier;
        $this->scopes = $scopes;
        $this->restUserIdentifierTransfer = $restUserIdentifierTransfer;
    }

    /**
     * @return string
     */
    public function getSurrogateIdentifier(): string
    {
        return $this->surrogateIdentifier;
    }

    /**
     * @return string
     */
    public function getNaturalIdentifier(): string
    {
        return $this->naturalIdentifier;
    }

    /**
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @return \Generated\Shared\Transfer\RestUserIdentifierTransfer|null
     */
    public function getRestUserIdentifierTransfer(): ?RestUserIdentifierTransfer
    {
        return $this->restUserIdentifierTransfer;
    }
}
