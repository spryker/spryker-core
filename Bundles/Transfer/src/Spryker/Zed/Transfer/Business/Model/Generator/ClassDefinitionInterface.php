<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

/**
 * @method string getName()
 */
interface ClassDefinitionInterface extends DefinitionInterface
{
    /**
     * @return array
     */
    public function getConstants(): array;

    /**
     * @return array
     */
    public function getProperties(): array;

    /**
     * @return array
     */
    public function getPropertyNameMap(): array;

    /**
     * @return array
     */
    public function getConstructorDefinition(): array;

    /**
     * @return array
     */
    public function getMethods(): array;

    /**
     * @return array
     */
    public function getNormalizedProperties(): array;

    /**
     * @return string|null
     */
    public function getDeprecationDescription(): ?string;

    /**
     * @return string[]
     */
    public function getUseStatements(): array;

    /**
     * @return string|null
     */
    public function getEntityNamespace(): ?string;

    /**
     * @return bool
     */
    public function debugMode(): bool;
}
