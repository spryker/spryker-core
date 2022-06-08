<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;

class OpenApiTagGenerator implements OpenApiSchemaFormatterInterface
{
    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(array $formattedData, ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array
    {
        $uniqueTags = array_unique(array_map(
            fn (ResourceContextTransfer $resource): string => $resource->getResourceTypeOrFail(),
            $apiApplicationSchemaContextTransfer->getResourceContexts()->getArrayCopy(),
        ));
        asort($uniqueTags);

        $formattedData['tags'] = $this->formatTags($uniqueTags);

        return $formattedData;
    }

    /**
     * @param array<string> $tags
     *
     * @return array<int, array<string, string>>
     */
    protected function formatTags(array $tags): array
    {
        $formattedTags = [];
        foreach ($tags as $tag) {
            $formattedTags[] = ['name' => $tag];
        }

        return $formattedTags;
    }
}
