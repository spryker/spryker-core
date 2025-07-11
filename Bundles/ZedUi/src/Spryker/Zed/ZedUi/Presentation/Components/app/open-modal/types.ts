import { ActionConfig } from '@spryker/actions';
import { TemplateRef, Type } from '@angular/core';

export interface OpenModalAction<TData = unknown> extends ActionConfig {
    component?: Type<unknown>;
    template?: TemplateRef<unknown>;
    confirm?: boolean | { title?: string; message?: string };
    form?: TData;
    url?: string;
    form_selector?: string;
    is_login?: boolean;
    width?: string;
}
