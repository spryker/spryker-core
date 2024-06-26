<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business\Model;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Orm\Zed\Content\Persistence\SpyContent;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Shared\ContentBanner\ContentBannerConfig;
use Spryker\Zed\Content\Dependency\ContentEvents;
use Spryker\Zed\ContentBannerDataImport\Business\Model\DataSet\ContentBannerDataSetInterface;
use Spryker\Zed\ContentBannerDataImport\Dependency\Service\ContentBannerDataImportToUtilEncodingInterface;
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

        $this->saveContentLocalizedBannerTerms(
            $dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_BANNER_TERMS],
            $contentBannerEntity->getIdContent(),
        );

        $this->addPublishEvents(
            ContentEvents::CONTENT_PUBLISH,
            $contentBannerEntity->getPrimaryKey(),
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    protected function saveContentBanner(DataSetInterface $dataSet): SpyContent
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
     * @param array $localizedBannerTerms
     * @param int $idContentBannerTerm
     *
     * @return void
     */
    protected function saveContentLocalizedBannerTerms(array $localizedBannerTerms, int $idContentBannerTerm): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $contentLocalizedCollection */
        $contentLocalizedCollection = SpyContentLocalizedQuery::create()
            ->filterByFkContent($idContentBannerTerm)
            ->find();
        $contentLocalizedCollection->delete();

        foreach ($localizedBannerTerms as $idLocale => $localizedBannerTerm) {
            if (!$idLocale) {
                $idLocale = null;
            }

            $localizedContentBannerEntity = SpyContentLocalizedQuery::create()
                ->filterByFkContent($idContentBannerTerm)
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();
            /** @var string $parameters */
            $parameters = $this->getEncodedParameters($localizedBannerTerm);
            $localizedContentBannerEntity->setParameters($parameters);

            $localizedContentBannerEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTermTransfer $contentBannerTermTransfer
     *
     * @return string|null
     */
    protected function getEncodedParameters(ContentBannerTermTransfer $contentBannerTermTransfer): ?string
    {
        return $this->utilEncoding->encodeJson($contentBannerTermTransfer->toArray());
    }
}
