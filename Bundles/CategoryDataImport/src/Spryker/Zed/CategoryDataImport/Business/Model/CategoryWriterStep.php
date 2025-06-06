<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Model;

use Exception;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryDataImport\Business\Exception\CategoryTemplateNotFoundException;
use Spryker\Zed\CategoryDataImport\Business\Model\Reader\CategoryReaderInterface;
use Spryker\Zed\CategoryDataImport\CategoryDataImportConfig;
use Spryker\Zed\CategoryDataImport\Dependency\Facade\CategoryDataImportToUrlFacadeInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CategoryWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    use InstancePoolingTrait;

    /**
     * @var string
     */
    public const KEY_NAME = 'name';

    /**
     * @var string
     */
    public const KEY_META_TITLE = 'meta_title';

    /**
     * @var string
     */
    public const KEY_META_DESCRIPTION = 'meta_description';

    /**
     * @var string
     */
    public const KEY_META_KEYWORDS = 'meta_keywords';

    /**
     * @var string
     */
    public const KEY_CATEGORY_KEY = 'category_key';

    /**
     * @var string
     */
    public const KEY_PARENT_CATEGORY_KEY = 'parent_category_key';

    /**
     * @var string
     */
    public const KEY_TEMPLATE_NAME = 'template_name';

    /**
     * @var \Spryker\Zed\CategoryDataImport\Business\Model\Reader\CategoryReaderInterface
     */
    protected $categoryReader;

    /**
     * @var \Spryker\Zed\CategoryDataImport\Dependency\Facade\CategoryDataImportToUrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\CategoryDataImport\CategoryDataImportConfig
     */
    protected CategoryDataImportConfig $categoryDataImportConfig;

    /**
     * @param \Spryker\Zed\CategoryDataImport\Business\Model\Reader\CategoryReaderInterface $categoryReader
     * @param \Spryker\Zed\CategoryDataImport\Dependency\Facade\CategoryDataImportToUrlFacadeInterface $urlFacade
     * @param \Spryker\Zed\CategoryDataImport\CategoryDataImportConfig $categoryDataImportConfig
     */
    public function __construct(
        CategoryReaderInterface $categoryReader,
        CategoryDataImportToUrlFacadeInterface $urlFacade,
        CategoryDataImportConfig $categoryDataImportConfig
    ) {
        $this->categoryReader = $categoryReader;
        $this->urlFacade = $urlFacade;
        $this->categoryDataImportConfig = $categoryDataImportConfig;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $categoryEntity = $this->findOrCreateCategory($dataSet);
        $this->findOrCreateAttributes($categoryEntity, $dataSet);
        $categoryNodeEntity = $this->findOrCreateNode($categoryEntity, $dataSet);

        $this->categoryReader->addCategory($categoryEntity, $categoryNodeEntity);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function findOrCreateCategory(DataSetInterface $dataSet)
    {
        $categoryEntity = SpyCategoryQuery::create()
            ->filterByCategoryKey($dataSet[static::KEY_CATEGORY_KEY])
            ->findOneOrCreate();

        $categoryEntity->fromArray($dataSet->getArrayCopy());

        if (!empty($dataSet[static::KEY_TEMPLATE_NAME])) {
            $categoryTemplateEntity = $this->getCategoryTemplate($dataSet);
            $categoryEntity->setFkCategoryTemplate($categoryTemplateEntity->getIdCategoryTemplate());
        }

        if ($categoryEntity->isNew() || $categoryEntity->isModified()) {
            $categoryEntity->save();
        }

        return $categoryEntity;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function findOrCreateAttributes(SpyCategory $categoryEntity, DataSetInterface $dataSet)
    {
        $localizedAttributeCollection = $dataSet[LocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES];
        foreach ($localizedAttributeCollection as $idLocale => $localizedAttributes) {
            if ($localizedAttributes === []) {
                continue;
            }

            $categoryAttributeEntity = SpyCategoryAttributeQuery::create()
                ->filterByCategory($categoryEntity)
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $categoryAttributeEntity->fromArray($localizedAttributes);

            if ($categoryAttributeEntity->isNew() || $categoryAttributeEntity->isModified()) {
                $categoryAttributeEntity->save();
            }
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    protected function findOrCreateNode(SpyCategory $categoryEntity, DataSetInterface $dataSet)
    {
        $categoryNodeEntity = SpyCategoryNodeQuery::create()
            ->filterByCategory($categoryEntity)
            ->findOneOrCreate();

        if (!empty($dataSet[static::KEY_PARENT_CATEGORY_KEY])) {
            $idParentCategoryNode = $this->categoryReader->getIdCategoryNodeByCategoryKey($dataSet[static::KEY_PARENT_CATEGORY_KEY]);
            $categoryNodeEntity->setFkParentCategoryNode($idParentCategoryNode);
        }

        $categoryNodeEntity->fromArray($dataSet->getArrayCopy());

        if ($categoryNodeEntity->isNew() || $categoryNodeEntity->isModified()) {
            $categoryNodeEntity->save();
        }

        $this->addToClosureTable($categoryNodeEntity);
        $this->addPublishEvents(CategoryEvents::CATEGORY_NODE_PUBLISH, $categoryNodeEntity->getIdCategoryNode());

        $urls = [];
        foreach ($categoryEntity->getAttributes() as $categoryAttributesEntity) {
            $idLocale = $categoryAttributesEntity->getFkLocale();
            $languageIdentifier = $this->getUrlPrefix($idLocale, $dataSet);
            $urlPathParts = [$languageIdentifier];
            if (!$categoryNodeEntity->getIsRoot()) {
                $parentUrl = $this->categoryReader->getParentUrl(
                    $dataSet[static::KEY_PARENT_CATEGORY_KEY],
                    $idLocale,
                );

                $urlPathParts = explode('/', ltrim($parentUrl, '/'));
                $urlPathParts[] = $categoryAttributesEntity->getName();
            }

            if ($categoryNodeEntity->getIsRoot()) {
                $this->addPublishEvents(CategoryEvents::CATEGORY_TREE_PUBLISH, $categoryNodeEntity->getIdCategoryNode());
            }

            $convertCallback = function ($value) {
                return mb_strtolower(str_replace(' ', '-', $value));
            };
            $urlPathParts = array_map($convertCallback, $urlPathParts);
            $url = '/' . implode('/', $urlPathParts);

            $urlEntity = SpyUrlQuery::create()
                ->filterByFkLocale($idLocale)
                ->filterByFkResourceCategorynode($categoryNodeEntity->getIdCategoryNode())
                ->findOneOrCreate();

            $urlEntity
                ->setUrl($url);

            if ($urlEntity->isNew()) {
                $urlEntity->save();
                $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());

                continue;
            }

            if ($urlEntity->isModified()) {
                $isPoolingEnabled = $this->isInstancePoolingEnabled();
                if ($isPoolingEnabled) {
                    $this->disableInstancePooling();
                }

                $urlTransfer = $this->mapUrlEntityToUrlTransfer($urlEntity);
                $this->urlFacade->updateUrl($urlTransfer);

                $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
                $urls[] = $urlTransfer->getUrlOrFail();

                if ($isPoolingEnabled) {
                    $this->enableInstancePooling();
                }
            }
        }

        if ($urls !== []) {
            $this->triggerPublishForUrlRedirectsByUrls($urls);
        }

        return $categoryNodeEntity;
    }

    /**
     * @param int $idLocale
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getUrlPrefix(int $idLocale, DataSetInterface $dataSet): string
    {
        if (!$this->categoryDataImportConfig->isFullLocaleNamesInUrlEnabled()) {
            return $this->getLanguageIdentifier($idLocale, $dataSet);
        }

        $locales = $dataSet[AddLocalesStep::KEY_LOCALES];
        foreach ($locales as $localeName => $localeId) {
            if ($idLocale === $localeId) {
                return str_replace('_', '-', strtolower($localeName));
            }
        }

        throw new Exception(sprintf('Could not find locale for id "%d"', $idLocale));
    }

    /**
     * @param int $idLocale
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getLanguageIdentifier(int $idLocale, DataSetInterface $dataSet): string
    {
        $locales = $dataSet[AddLocalesStep::KEY_LOCALES];
        foreach ($locales as $localeName => $localeId) {
            if ($idLocale === $localeId) {
                return mb_substr($localeName, 0, 2);
            }
        }

        throw new Exception(sprintf('Could not extract language identifier for idLocale "%s"', $idLocale));
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return void
     */
    protected function addToClosureTable(SpyCategoryNode $categoryNodeEntity)
    {
        if ($categoryNodeEntity->getFkParentCategoryNode() !== null) {
            $categoryClosureEntityCollection = SpyCategoryClosureTableQuery::create()
                ->findByFkCategoryNodeDescendant($categoryNodeEntity->getFkParentCategoryNode());

            foreach ($categoryClosureEntityCollection as $categoryClosureEntity) {
                $newCategoryClosureTableEntity = SpyCategoryClosureTableQuery::create()
                    ->filterByFkCategoryNode($categoryClosureEntity->getFkCategoryNode())
                    ->filterByFkCategoryNodeDescendant($categoryNodeEntity->getIdCategoryNode())
                    ->findOneOrCreate();

                $newCategoryClosureTableEntity
                    ->setDepth($categoryClosureEntity->getDepth() + 1);

                if ($newCategoryClosureTableEntity->isNew() || $newCategoryClosureTableEntity->isModified()) {
                    $newCategoryClosureTableEntity->save();
                }
            }
        }

        $categoryClosureTableEntity = SpyCategoryClosureTableQuery::create()
            ->filterByFkCategoryNode($categoryNodeEntity->getIdCategoryNode())
            ->filterByFkCategoryNodeDescendant($categoryNodeEntity->getIdCategoryNode())
            ->findOneOrCreate();

        $categoryClosureTableEntity
            ->setDepth(0);

        if ($categoryClosureTableEntity->isNew() || $categoryClosureTableEntity->isModified()) {
            $categoryClosureTableEntity->save();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\CategoryDataImport\Business\Exception\CategoryTemplateNotFoundException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplate
     */
    protected function getCategoryTemplate(DataSetInterface $dataSet): SpyCategoryTemplate
    {
        $categoryTemplateEntity = SpyCategoryTemplateQuery::create()->findOneByName($dataSet[static::KEY_TEMPLATE_NAME]);
        if (!$categoryTemplateEntity) {
            throw new CategoryTemplateNotFoundException(sprintf('CategoryTemplate with template name "%s" not found!', $dataSet[static::KEY_TEMPLATE_NAME]));
        }

        return $categoryTemplateEntity;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function mapUrlEntityToUrlTransfer(SpyUrl $urlEntity): UrlTransfer
    {
        return (new UrlTransfer())
            ->fromArray($urlEntity->toArray(), true);
    }

    /**
     * @param list<string> $toUrls
     *
     * @return void
     */
    protected function triggerPublishForUrlRedirectsByUrls(array $toUrls): void
    {
        $urlEntities = SpyUrlQuery::create()
            ->useSpyUrlRedirectQuery()
                ->filterByToUrl_In($toUrls)
            ->endUse()
            ->find();

        foreach ($urlEntities as $urlEntity) {
            $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
        }
    }
}
