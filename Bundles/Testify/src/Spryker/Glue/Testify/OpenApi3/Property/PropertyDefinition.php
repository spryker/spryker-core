<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Property;

use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

class PropertyDefinition
{
    /**
     * @var string Class name
     */
    protected $type;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;

        if (new $type() instanceof ReferableInterface) {
            $this;
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return static
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }
}
