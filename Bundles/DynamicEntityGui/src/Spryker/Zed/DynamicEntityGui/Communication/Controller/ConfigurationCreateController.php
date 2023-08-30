<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\DynamicEntityGui\Communication\DynamicEntityGuiCommunicationFactory getFactory()
 */
class ConfigurationCreateController extends AbstractController
{
    /**
     * @var string
     */
    protected const URL_PARAM_TABLE_NAME = 'table-name';

    /**
     * @var string
     */
    protected const FIELD_TABLE_NAME = 'table_name';

    /**
     * @var string
     */
    protected const KEY_FORM = 'form';

    /**
     * @var string
     */
    protected const MESSAGE_DYNAMIC_DATA_CONFIGURATION_CREATED = 'Configuration created successfully';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createDynamicDataConfigurationFormDataProvider();

        $createDynamicDataConfigurationForm = $this->getFactory()
            ->getCreateDynamicDataConfigurationForm(
                $dataProvider->getOptions(),
            )
            ->handleRequest($request);

        if ($createDynamicDataConfigurationForm->isSubmitted() && $createDynamicDataConfigurationForm->isValid()) {
            return $this->createDynamicEntityConfiguration($createDynamicDataConfigurationForm);
        }

        return $this->viewResponse([
            static::KEY_FORM => $createDynamicDataConfigurationForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $dynamicDataConfigurationForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    protected function createDynamicEntityConfiguration(FormInterface $dynamicDataConfigurationForm)
    {
        $tableName = $dynamicDataConfigurationForm->getData()[static::FIELD_TABLE_NAME]->getTableName();

        $dynamicEntityConfigurationCollectionRequestTransfer = $this->getFactory()
            ->createDynamicDataConfigurationMapper()
            ->mapInitialConfigurationDataToCollectionRequestTransfer($tableName);

        $dynamicEntityConfigurationCollectionResponseTransfer = $this->getFactory()
            ->getDynamicEntityFacade()
            ->createDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionRequestTransfer);

        if ($dynamicEntityConfigurationCollectionResponseTransfer->getErrors()->count() === 0) {
            $this->addSuccessMessage(static::MESSAGE_DYNAMIC_DATA_CONFIGURATION_CREATED);

            $redirectUrl = Url::generate(
                DynamicEntityGuiConfig::URL_DYNAMIC_DATA_CONFIGURATION_EDIT,
                [static::URL_PARAM_TABLE_NAME => $tableName],
            )->build();

            return $this->redirectResponse($redirectUrl);
        }

        foreach ($dynamicEntityConfigurationCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessageOrFail());
        }

        return $this->viewResponse([
            static::KEY_FORM => $dynamicDataConfigurationForm->createView(),
        ]);
    }
}
