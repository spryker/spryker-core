import { ActionConfig } from '@spryker/actions';

export interface SubmitFormActionConfig extends ActionConfig {
    form_selector?: string;
}
