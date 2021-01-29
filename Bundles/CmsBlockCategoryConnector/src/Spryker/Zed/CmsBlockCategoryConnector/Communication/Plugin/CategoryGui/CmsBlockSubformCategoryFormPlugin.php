<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin\CategoryGui;

use Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 */
class CmsBlockSubformCategoryFormPlugin extends AbstractPlugin implements CategoryFormPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extends category form with a subform for cms block.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $formType = $this->getFactory()
            ->createCategoryType();

        $dataProvider = $this->getFactory()
            ->createCategoryDataProvider();

        $categoryTransfer = $builder->getData();
        $dataProvider->getData($categoryTransfer);

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions()
        );
    }
}
