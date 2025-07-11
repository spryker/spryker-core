import { ActionConfig } from '@spryker/actions';

export interface SubmitAjaxFormActionConfig extends ActionConfig {
    type: 'submit-ajax-form';
    ajax_form_selector: string;
}
