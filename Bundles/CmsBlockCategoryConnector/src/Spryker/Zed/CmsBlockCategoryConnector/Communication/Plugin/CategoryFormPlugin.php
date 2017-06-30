<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin;


use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Dependency\Plugin\CategoryFormPluginInterface;
use Spryker\Zed\Category\Dependency\Plugin\CategoryRelationUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacade getFacade()
 */
class CategoryFormPlugin extends AbstractPlugin implements CategoryFormPluginInterface, CategoryRelationUpdatePluginInterface
{

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder)
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

    /**
     * @param CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->getFacade()
            ->updateCategoryCmsBlockRelations($categoryTransfer);
    }

}