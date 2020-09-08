<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentNavigationDataImport\Business\Step;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Orm\Zed\Content\Persistence\SpyContent;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Shared\ContentNavigation\ContentNavigationConfig;
use Spryker\Zed\Content\Dependency\ContentEvents;
use Spryker\Zed\ContentNavigationDataImport\Business\DataSet\ContentNavigationDataSetInterface;
use Spryker\Zed\ContentNavigationDataImport\Dependency\Service\ContentNavigationDataImportToUtilEncodingInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentNavigationWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ContentNavigationDataImport\Dependency\Service\ContentNavigationDataImportToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Zed\ContentNavigationDataImport\Dependency\Service\ContentNavigationDataImportToUtilEncodingInterface $utilEncoding
     */
    public function __construct(ContentNavigationDataImportToUtilEncodingInterface $utilEncoding)
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
        $contentNavigationEntity = $this->saveContentNavigation($dataSet);

        $this->saveContentLocalizedNavigationTerms(
            $dataSet[ContentNavigationDataSetInterface::CONTENT_LOCALIZED_NAVIGATION_TERMS],
            $contentNavigationEntity->getIdContent()
        );

        $this->addPublishEvents(
            ContentEvents::CONTENT_PUBLISH,
            $contentNavigationEntity->getPrimaryKey()
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    protected function saveContentNavigation(DataSetInterface $dataSet): SpyContent
    {
        $contentNavigationEntity = SpyContentQuery::create()
            ->filterByKey($dataSet[ContentNavigationDataSetInterface::CONTENT_NAVIGATION_KEY])
            ->findOneOrCreate();

        $contentNavigationEntity->fromArray($dataSet->getArrayCopy());
        $contentNavigationEntity->setContentTermKey(ContentNavigationConfig::CONTENT_TERM_NAVIGATION);
        $contentNavigationEntity->setContentTypeKey(ContentNavigationConfig::CONTENT_TYPE_NAVIGATION);

        $contentNavigationEntity->save();

        return $contentNavigationEntity;
    }

    /**
     * @param array $localizedNavigationTerms
     * @param int $idContentNavigationTerm
     *
     * @return void
     */
    protected function saveContentLocalizedNavigationTerms(array $localizedNavigationTerms, int $idContentNavigationTerm): void
    {
        SpyContentLocalizedQuery::create()
            ->filterByFkContent($idContentNavigationTerm)
            ->find()
            ->delete();

        foreach ($localizedNavigationTerms as $idLocale => $localizedNavigationTerm) {
            if (!$idLocale) {
                $idLocale = null;
            }

            $localizedContentNavigationEntity = SpyContentLocalizedQuery::create()
                ->filterByFkContent($idContentNavigationTerm)
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();
            $localizedContentNavigationEntity->setParameters(
                $this->getEncodedParameters($localizedNavigationTerm)
            );

            $localizedContentNavigationEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ContentNavigationTermTransfer $contentNavigationTermTransfer
     *
     * @return string|null
     */
    protected function getEncodedParameters(ContentNavigationTermTransfer $contentNavigationTermTransfer): ?string
    {
        return $this->utilEncoding->encodeJson($contentNavigationTermTransfer->toArray());
    }
}
