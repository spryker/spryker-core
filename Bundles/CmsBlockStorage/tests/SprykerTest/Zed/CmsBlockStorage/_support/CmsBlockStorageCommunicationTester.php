<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockStorage;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsBlockStorageCommunicationTester extends Actor
{
    use _generated\CmsBlockStorageCommunicationTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->setDependency(StoreDependencyProvider::PLUGINS_STORE_EXPANDER, [
            $this->createStoreStorageStoreExpanderPluginMock(),
        ]);
    }

    /**
     * @param array<int> $storeIds
     * @param array<int> $localeIds
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function createCmsBlock(array $storeIds, array $localeIds): CmsBlockTransfer
    {
        $cmsBlockTransfer = $this->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_STORES => $storeIds,
            ],
        ]);

        $translations = new ArrayObject();

        foreach ($localeIds as $localeId) {
            $translations->append((new CmsBlockGlossaryPlaceholderTranslationTransfer())
                ->setFkLocale($localeId)
                ->setTranslation('Test translation'));
        }

        $placeholder = new CmsBlockGlossaryPlaceholderTransfer();
        $placeholder->setTranslations($translations);
        $placeholder->setPlaceholder('placeholder');
        $placeholder->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());
        $placeholder->setTemplateName($cmsBlockTransfer->getTemplateName());

        $glossary = new CmsBlockGlossaryTransfer();
        $glossary->addGlossaryPlaceholder($placeholder);

        $this->getLocator()->cmsBlock()->facade()->saveGlossary($glossary);

        return $cmsBlockTransfer;
    }

    /**
     * @return \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY);

        $storeStorageStoreExpanderPluginMock = Stub::makeEmpty(StoreExpanderPluginInterface::class, [
            'expand' => $storeTransfer,
        ]);

        return $storeStorageStoreExpanderPluginMock;
    }
}
