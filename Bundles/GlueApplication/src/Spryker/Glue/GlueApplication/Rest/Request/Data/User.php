<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

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
     * @param string $surrogateIdentifier
     * @param string $naturalIdentifier
     * @param array $scopes
     */
    public function __construct(string $surrogateIdentifier, string $naturalIdentifier, array $scopes = [])
    {
        $this->surrogateIdentifier = $surrogateIdentifier;
        $this->naturalIdentifier = $naturalIdentifier;
        $this->scopes = $scopes;
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
}
