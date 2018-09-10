<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin;

use Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 */
class CmsBlockCategoryFormPlugin extends AbstractPlugin implements CmsBlockFormPluginInterface
{
    /**
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $formType = $this->getFactory()
            ->createCmsBlockType();

        $dataProvider = $this->getFactory()
            ->createCmsBlockCategoryDataProvider();

        $cmsBlockTransfer = $builder->getData();
        $dataProvider->getData($cmsBlockTransfer);

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions()
        );
    }
}
