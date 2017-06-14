<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector\Business\Collector\Storage;


use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\CmsBlock\CmsBlockConstants;
use Spryker\Zed\CmsBlockCollector\Persistence\Collector\Storage\Propel\CmsBlockCollectorQuery;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsBlockCollector extends AbstractStoragePropelCollector
{

    /**
     * @param UtilDataReaderServiceInterface $utilDataReaderService
     */
    public function __construct(UtilDataReaderServiceInterface $utilDataReaderService)
    {
        parent::__construct($utilDataReaderService);
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        return [
            'id' => $collectItemData[CmsBlockCollectorQuery::COL_ID_CMS_BLOCK],
            'valid_from' => $collectItemData[CmsBlockCollectorQuery::COL_VALID_FROM],
            'valid_to' => $collectItemData[CmsBlockCollectorQuery::COL_VALID_TO],
            'is_active' => $collectItemData[CmsBlockCollectorQuery::COL_IS_ACTIVE],
            'template' => $collectItemData[CmsBlockCollectorQuery::COL_TEMPLATE_PATH],
            'placeholders' => $this->extractPlaceholders(
                $collectItemData[CmsBlockCollectorQuery::COL_PLACEHOLDERS],
                $collectItemData[CmsBlockCollectorQuery::COL_GLOSSARY_KEYS]
            ),
            'name' => $collectItemData[CmsBlockCollectorQuery::COL_NAME],
        ];
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsBlockConstants::RESOURCE_TYPE_CMS_BLOCK;
    }

    /**
     * @param string $placeholders
     * @param string $glossaryKeys
     *
     * @return array
     */
    protected function extractPlaceholders($placeholders, $glossaryKeys)
    {
        $separator = ',';
        $placeholderNames = explode($separator, trim($placeholders));
        $glossaryKeys = explode($separator, trim($glossaryKeys));

        $step = 0;
        $placeholderCollection = [];
        foreach ($placeholderNames as $name) {
            $placeholderCollection[$name] = $glossaryKeys[$step];
            $step++;
        }

        return $placeholderCollection;
    }

}
