<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Controller;

use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\DynamicEntityGui\Communication\DynamicEntityGuiCommunicationFactory getFactory()
 */
class ConfigurationEditController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_TABLE_NAME = 'table-name';

    /**
     * @var string
     */
    protected const MESSAGE_CONFIGURATION_UPDATED = 'Configuration is updated successfully';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_TABLE_NOT_EXIST = 'Table with name `%s` does not exist';

    /**
     * @var string
     */
    protected const MESSAGE_NAME_PLACEHOLDER = '%s';

    /**
     * @var string
     */
    protected const KEY_FORM = 'form';

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const KEY_FIELD_DEFINITIONS = 'field_definitions';

    /**
     * @var string
     */
    protected const KEY_FIELD_NAME = 'field_name';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PLACEHOLDER = '`%s` - %s';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    public function indexAction(Request $request)
    {
        $tableName = $request->get(static::REQUEST_TABLE_NAME);

        $dataProvider = $this->getFactory()->createUpdateDynamicDataConfigurationFormDataProvider();
        $dynamicDataConfigurationTransfer = $dataProvider->getData($tableName);

        if ($dynamicDataConfigurationTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_ERROR_TABLE_NOT_EXIST, [
                static::MESSAGE_NAME_PLACEHOLDER => $tableName,
            ]);

            return $this->redirectResponse(DynamicEntityGuiConfig::URL_DYNAMIC_DATA_CONFIGURATION_LIST);
        }

        $updateDynamicDataConfigurationForm = $this->getFactory()
            ->getUpdateDynamicDataConfigurationForm(
                $dynamicDataConfigurationTransfer,
                $dataProvider->getOptions($tableName),
            )
            ->handleRequest($request);

        if ($updateDynamicDataConfigurationForm->isSubmitted() && $updateDynamicDataConfigurationForm->isValid()) {
            return $this->updateDynamicEntityConfiguration($updateDynamicDataConfigurationForm);
        }

        return $this->viewResponse([
            static::KEY_FORM => $updateDynamicDataConfigurationForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $dynamicDataConfigurationForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    protected function updateDynamicEntityConfiguration(FormInterface $dynamicDataConfigurationForm)
    {
        $dynamicDataConfiguration = $dynamicDataConfigurationForm->getData();

        $dynamicEntityConfigurationCollectionRequestTransfer = $this->getFactory()
            ->createDynamicDataConfigurationMapper()
            ->mapDynamicDataConfigurationDataToCollectionRequestTransfer($dynamicDataConfiguration);

        $dynamicEntityConfigurationCollectionResponseTransfer = $this->getFactory()
            ->getDynamicEntityFacade()
            ->updateDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionRequestTransfer);

        if ($dynamicEntityConfigurationCollectionResponseTransfer->getErrors()->count() === 0) {
            $this->addSuccessMessage(static::MESSAGE_CONFIGURATION_UPDATED);

            return $this->redirectResponse(DynamicEntityGuiConfig::URL_DYNAMIC_DATA_CONFIGURATION_LIST);
        }

        $dynamicDataConfigurationForm = $this->mapErrorsToForm($dynamicDataConfigurationForm, $dynamicEntityConfigurationCollectionResponseTransfer);

        return $this->viewResponse([
            static::KEY_FORM => $dynamicDataConfigurationForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $dynamicDataConfigurationForm
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function mapErrorsToForm(
        FormInterface $dynamicDataConfigurationForm,
        DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
    ): FormInterface {
        foreach ($dynamicEntityConfigurationCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $errorAddedToDefinitionForm = $this->addFormErrorToFieldDefinitionForm($dynamicDataConfigurationForm, $errorTransfer);

            if ($errorAddedToDefinitionForm === true) {
                continue;
            }

            $dynamicDataConfigurationForm->addError($this->mapFormErrorTransferToFormError($errorTransfer));
        }

        return $dynamicDataConfigurationForm;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $dynamicDataConfigurationForm
     * @param \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
     *
     * @return bool
     */
    protected function addFormErrorToFieldDefinitionForm(FormInterface $dynamicDataConfigurationForm, ErrorTransfer $errorTransfer): bool
    {
        $fieldDefinitionsForms = $dynamicDataConfigurationForm->get(static::KEY_FIELD_DEFINITIONS)->all();
        /** @var \Symfony\Component\Form\FormInterface $fieldDefinitionsForm */
        foreach ($fieldDefinitionsForms as $fieldDefinitionsForm) {
            $data = $fieldDefinitionsForm->getData();

            if (
                isset($fieldDefinitionsForm->getData()[static::KEY_FIELD_NAME]) &&
                $fieldDefinitionsForm->getData()[static::KEY_FIELD_NAME] === $errorTransfer->getEntityIdentifier()
            ) {
                $fieldDefinitionsForm->addError($this->mapFormErrorTransferToFormError($errorTransfer));

                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
     *
     * @return \Symfony\Component\Form\FormError
     */
    protected function mapFormErrorTransferToFormError(ErrorTransfer $errorTransfer): FormError
    {
        $errorMessage = sprintf(
            static::ERROR_MESSAGE_PLACEHOLDER,
            $errorTransfer->getEntityIdentifier(),
            $errorTransfer->getMessage(),
        );

        return new FormError($errorMessage);
    }
}
