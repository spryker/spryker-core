<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Mapper;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface;

class ContentMapper implements ContentMapperInterface
{
    protected const URL_LIST_CONTENT_BY_TYPE = '/content-gui/list-content-by-type';
    protected const URL_PARAM_TYPE = 'type';
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
     * @param string[] $contentTypes
     *
     * @return string[][]
     */
    public function mapEditorContentTypes(array $contentTypes): array
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
        return Url::generate(static::URL_LIST_CONTENT_BY_TYPE, [static::URL_PARAM_TYPE => $contentType])->build();
    }
}
