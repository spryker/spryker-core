<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin;

/**
 * Provides extension capabilities for generating documentation content by collected and formatter data from annotations.
 */
interface ContentGeneratorStrategyPluginInterface
{
    /**
     * Specification:
     * - Converts the generated documentation array into a formatted string.
     *
     * @api
     *
     * @param array<mixed> $formattedData
     *
     * @return string
     */
    public function generateContent(array $formattedData): string;
}
