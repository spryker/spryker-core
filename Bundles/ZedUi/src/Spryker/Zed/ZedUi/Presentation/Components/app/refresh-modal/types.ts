import { ActionConfig } from '@spryker/actions';

export interface RefreshModalActionConfig extends ActionConfig {
    data?: unknown;
    form?: string;
    modalId?: string;
}
