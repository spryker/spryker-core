<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundlePageSearch;

use Codeception\Actor;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateConfigurableBundlePageSearchPublishListener;
use Spryker\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateConfigurableBundlePageSearchUnpublishListener;
use Spryker\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Event\Listener\ProductImageSetConfigurableBundlePageSearchPublishListener;
use Spryker\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Search\ConfigurableBundleTemplatePageMapPlugin;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Search\SearchDependencyProvider;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigurableBundlePageSearchCommunicationTester extends Actor
{
    use _generated\ConfigurableBundlePageSearchCommunicationTesterActions;

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function createConfigurableBundleTemplate(array $data = []): ConfigurableBundleTemplateTransfer
    {
        $defaultData = [
            ConfigurableBundleTemplateTransfer::NAME => 'configurable_bundle.templates.test-name',
            ConfigurableBundleTemplateTransfer::UUID => uniqid(),
            ConfigurableBundleTemplateTransfer::IS_ACTIVE => true,
            ConfigurableBundleTemplateTransfer::TRANSLATIONS => $this->createTemplateTranslationsForAvailableLocales(),
        ];

        return $this->haveConfigurableBundleTemplate(array_merge($data, $defaultData));
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function createTemplateTranslationsForAvailableLocales(array $data = []): array
    {
        $availableLocaleTransfers = $this->getLocator()
            ->locale()
            ->facade()
            ->getLocaleCollection();

        $configurableBundleTemplateTranslationTransfers = [];

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $defaultData = [
                ConfigurableBundleTemplateTranslationTransfer::NAME => 'test-name',
                ConfigurableBundleTemplateTranslationTransfer::LOCALE => $localeTransfer,
            ];

            $configurableBundleTemplateTranslationTransfers[] = array_merge($defaultData, $data);
        }

        return $configurableBundleTemplateTranslationTransfers;
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface
     */
    public function createConfigurableBundleTemplatePublishListener(): EventBulkHandlerInterface
    {
        $configurableBundleTemplatePublishListener = new ConfigurableBundleTemplateConfigurableBundlePageSearchPublishListener();
        $configurableBundleTemplatePublishListener->setFacade($this->getFacade());

        return $configurableBundleTemplatePublishListener;
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface
     */
    public function createConfigurableBundleTemplateUnpublishListener(): EventBulkHandlerInterface
    {
        $configurableBundleTemplateUnpublishListener = new ConfigurableBundleTemplateConfigurableBundlePageSearchUnpublishListener();
        $configurableBundleTemplateUnpublishListener->setFacade($this->getFacade());

        return $configurableBundleTemplateUnpublishListener;
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface
     */
    public function createProductImageSetConfigurableBundlePageSearchPublishListener(): EventBulkHandlerInterface
    {
        $configurableBundleTemplateUnpublishListener = new ProductImageSetConfigurableBundlePageSearchPublishListener();
        $configurableBundleTemplateUnpublishListener->setFacade($this->getFacade());

        return $configurableBundleTemplateUnpublishListener;
    }

    /**
     * @return void
     */
    public function setDependencies(): void
    {
        $this->setQueueAdaptersDependency();
        $this->setSearchPageMapPluginsDependency();
    }

    /**
     * @return void
     */
    protected function setQueueAdaptersDependency(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    protected function setSearchPageMapPluginsDependency(): void
    {
        $this->setDependency(SearchDependencyProvider::PLUGIN_SEARCH_PAGE_MAPS, function () {
            return [
                new ConfigurableBundleTemplatePageMapPlugin(),
            ];
        });
    }
}
