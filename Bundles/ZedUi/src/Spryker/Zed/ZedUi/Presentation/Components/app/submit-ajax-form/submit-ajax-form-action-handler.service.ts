import { Injectable, Injector } from '@angular/core';
import { ActionHandler } from '@spryker/actions';
import { Observable, of } from 'rxjs';

import { SubmitAjaxFormActionConfig } from './types';

@Injectable({
    providedIn: 'root',
})
export class SubmitAjaxFormActionHandlerService implements ActionHandler<unknown, void> {
    handleAction(injector: Injector, config: SubmitAjaxFormActionConfig): Observable<void> {
        if (typeof config?.ajax_form_selector !== 'string') {
            console.error('Submit ajax form action: No valid ajax form selector provided');
            return of(undefined);
        }

        try {
            const ajaxFormElement = document.querySelector<HTMLElement>(config.ajax_form_selector);
            if (!ajaxFormElement) {
                console.error(
                    `Submit ajax form action: No ajax form found with selector "${config.ajax_form_selector}"`,
                );
                return of(undefined);
            }

            const formElement = ajaxFormElement.querySelector<HTMLFormElement>('form');
            if (!formElement) {
                console.error(
                    `Submit ajax form action: No form element found within ajax form "${config.ajax_form_selector}"`,
                );
                return of(undefined);
            }

            const submitButton = formElement.querySelector('button[type="submit"], input[type="submit"]');

            if (!submitButton) {
                console.error(`Submit ajax form action: No submit button found within form`);
                return of(undefined);
            }

            const buttonId = 'temp-locked-button';
            submitButton.setAttribute('data-locked', 'true');
            submitButton.setAttribute('id', buttonId);

            const clickEvent = new MouseEvent('click', {
                bubbles: true,
                cancelable: true,
                view: window,
            });

            submitButton.dispatchEvent(clickEvent);

            queueMicrotask(() => {
                submitButton.removeAttribute('data-locked');
            });

            return of(undefined);
        } catch (error) {
            console.error('Submit ajax form action: Error during form submission', error);
            return of(undefined);
        }
    }
}
