<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CmsBlockCategoryForm;
use Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method CmsBlockCategoryConnectorCommunicationFactory getFactory()
 */
class CmsBlockCategoryFormPlugin extends AbstractPlugin implements CmsBlockFormPluginInterface
{

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $formType = $this->getFactory()
            ->createCmsBlockCategoryType();

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