<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business\Model;

use Orm\Zed\Content\Persistence\SpyContent;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Shared\ContentBanner\ContentBannerConfig;
use Spryker\Zed\Content\Dependency\ContentEvents;
use Spryker\Zed\ContentBannerDataImport\Business\Model\DataSet\ContentBannerDataSetInterface;
use Spryker\Zed\ContentBannerDataImport\Dependency\Service\ContentBannerDataImportToUtilEncodingInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentBannerWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ContentBannerDataImport\Dependency\Service\ContentBannerDataImportToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Zed\ContentBannerDataImport\Dependency\Service\ContentBannerDataImportToUtilEncodingInterface $utilEncoding
     */
    public function __construct(ContentBannerDataImportToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $contentBannerEntity = $this->saveContentBanner($dataSet);

        $this->saveContentBannerLocalizedItems(
            $dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_ITEMS],
            $contentBannerEntity->getIdContent()
        );

        $this->addPublishEvents(
            ContentEvents::CONTENT_PUBLISH,
            $contentBannerEntity->getPrimaryKey()
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    private function saveContentBanner(DataSetInterface $dataSet): SpyContent
    {
        $contentBannerEntity = SpyContentQuery::create()
            ->filterByKey($dataSet[ContentBannerDataSetInterface::CONTENT_BANNER_KEY])
            ->findOneOrCreate();

        $contentBannerEntity->fromArray($dataSet->getArrayCopy());
        $contentBannerEntity->setContentTermKey(ContentBannerConfig::CONTENT_TERM_BANNER);
        $contentBannerEntity->setContentTypeKey(ContentBannerConfig::CONTENT_TYPE_BANNER);

        $contentBannerEntity->save();

        return $contentBannerEntity;
    }

    /**
     * @param array $localizedItems
     * @param int $idContentBanner
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    private function saveContentBannerLocalizedItems(array $localizedItems, int $idContentBanner): void
    {
        SpyContentLocalizedQuery::create()
            ->filterByFkContent($idContentBanner)
            ->find()
            ->delete();

        $defaultLocaleIsPresent = false;
        foreach ($localizedItems as $localeId => $attributes) {
            if (!$localeId) {
                $localeId = null;
                $defaultLocaleIsPresent = true;
            }

            $contentBannerLocalizedItem = SpyContentLocalizedQuery::create()
                ->filterByFkContent($idContentBanner)
                ->filterByFkLocale($localeId)
                ->findOneOrCreate();

            $contentBannerLocalizedItem->setParameters(
                $this->utilEncoding->encodeJson($attributes->toArray())
            );

            $contentBannerLocalizedItem->save();
        }

        if (!$defaultLocaleIsPresent) {
            throw new InvalidDataException('Default locale is absent');
        }
    }
}
