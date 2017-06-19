<?php

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\CmsBlockProductConnector\Communication\CmsBlockProductConnectorCommunicationFactory;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method CmsBlockProductConnectorCommunicationFactory getFactory()
 */
class CmsBlockProductAbstractFormPlugin extends AbstractPlugin implements CmsBlockFormPluginInterface
{

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $formType = $this->getFactory()
            ->createCmsBlockProductAbstractType();

        $dataProvider = $this->getFactory()
            ->createCmsBlockProductDataProvider();

        $cmsBlockTransfer = $builder->getData();
        $dataProvider->getData($cmsBlockTransfer);

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions()
        );
    }

}