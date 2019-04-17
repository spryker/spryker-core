<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Mapper;

use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface;

class ContentMapper implements ContentMapperInterface
{
    protected const URL_TEMPLATE_CONTENT_TYPE = '/content-gui/list-content-by-type?type=%s';
    protected const KEY_TYPE = 'type';
    protected const KEY_NAME = 'name';
    protected const KEY_CONTENT_LIST_URL = 'contentListUrl';

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(ContentGuiToTranslatorFacadeInterface $translatorFacade)
    {
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param array $contentTypes
     *
     * @return array
     */
    public function mapEnabledContentTypesForEditor(array $contentTypes): array
    {
        $contentList = [];

        foreach ($contentTypes as $contentType) {
            $contentList[] = [
                static::KEY_TYPE => $contentType,
                static::KEY_NAME => $this->translatorFacade->trans($contentType),
                static::KEY_CONTENT_LIST_URL => $this->generateContentTypeUrl($contentType),
            ];
        }

        return $contentList;
    }

    /**
     * @param string $contentType
     *
     * @return string
     */
    protected function generateContentTypeUrl(string $contentType): string
    {
        return sprintf(static::URL_TEMPLATE_CONTENT_TYPE, $contentType);
    }
}
