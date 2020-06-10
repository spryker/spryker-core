<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsPageSearchConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\CmsPageSearch\Business\Search\DataMapper\CmsPageSearchDataMapper::TYPE_CMS_PAGE
     */
    protected const TYPE_CMS_PAGE = 'cms_page';
    /**
     * @uses \Spryker\Zed\CmsPageSearch\Business\Search\DataMapper\CmsPageSearchDataMapper::KEY_URL
     */
    protected const KEY_URL = 'url';
    /**
     * @uses \Spryker\Zed\CmsPageSearch\Business\Search\DataMapper\CmsPageSearchDataMapper::KEY_NAME
     */
    protected const KEY_NAME = 'name';
    /**
     * @uses \Spryker\Zed\CmsPageSearch\Business\Search\DataMapper\CmsPageSearchDataMapper::KEY_TYPE
     */
    protected const KEY_TYPE = 'type';
    /**
     * @uses \Spryker\Zed\CmsPageSearch\Business\Search\DataMapper\CmsPageSearchDataMapper::KEY_ID_CMS_PAGE
     */
    protected const KEY_ID_CMS_PAGE = 'id_cms_page';

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getCmsPageSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @phpstan-param array<string, string> $data
     *
     * @phpstan-return array<string, string>
     *
     * @param string[] $data
     *
     * @return string[]
     */
    public function getSearchResultData(array $data): array
    {
        return array_merge(
            $this->getCoreSearchResultData($data),
            $this->getProjectSearchResultData($data)
        );
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @phpstan-return array<string, string>
     *
     * @param string[] $data
     *
     * @return string[]
     */
    protected function getCoreSearchResultData(array $data): array
    {
        return [
            static::KEY_ID_CMS_PAGE => $data[static::KEY_ID_CMS_PAGE],
            static::KEY_NAME => $data[static::KEY_NAME],
            static::KEY_TYPE => static::TYPE_CMS_PAGE,
            static::KEY_URL => $data[static::KEY_URL],
        ];
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @phpstan-return array<string, string>
     *
     * @param string[] $data
     *
     * @return string[]
     */
    protected function getProjectSearchResultData(array $data): array
    {
        return [];
    }
}
