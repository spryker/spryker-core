<?php


namespace Spryker\Zed\CmsGui\Communication\Controller;


use Spryker\Zed\CmsGui\CmsGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class CreateBlockController extends AbstractController
{
    public function indexAction(Request $request)
    {
        $this->getFactory()
            ->getCmsFacade()
            ->syncTemplate(CmsGuiConfig::CMS_FOLDER_PATH);

        $dataProvider = $this->getFactory()->createCmsBlockFormDataProvider();



    }

}