<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\PathMethodDataTransfer;

class OpenApiTagGenerator implements OpenApiTagGeneratorInterface
{
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @return array
     */
    public function getTags(): array
    {
        $uniqueTags = array_unique($this->tags);
        asort($uniqueTags);

        return $this->formatTags($uniqueTags);
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return void
     */
    public function addTag(PathMethodDataTransfer $pathMethodDataTransfer): void
    {
        if ($pathMethodDataTransfer->getResource()) {
            $this->tags[] = $pathMethodDataTransfer->getResource();
        }
    }

    /**
     * @param string[] $tags
     *
     * @return array
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
