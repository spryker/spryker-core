import { Injectable, Injector } from '@angular/core';
import { ActionHandler } from '@spryker/actions';
import { Observable, of } from 'rxjs';

import { SubmitFormActionConfig } from './types';

@Injectable({
    providedIn: 'root',
})
export class SubmitFormActionHandlerService implements ActionHandler<unknown, void> {
    handleAction(injector: Injector, config: SubmitFormActionConfig): Observable<void> {
        if (!config.form_selector || typeof config.form_selector !== 'string') {
            console.error('Submit form action: No valid form selector provided');
            return of(void 0);
        }

        const formElement = document.querySelector(config.form_selector) as HTMLFormElement;

        if (!formElement) {
            console.error(`Submit form action: No form found with selector "${config.form_selector}"`);
            return of(void 0);
        }

        const createAndSubmitDynamicForm = (sourceForm: HTMLFormElement): boolean => {
            const formAction = sourceForm.getAttribute('action') || '';
            const formMethod = (sourceForm.getAttribute('method') || 'POST').toUpperCase();

            const dynamicForm = document.createElement('form');
            dynamicForm.style.display = 'none';
            dynamicForm.action = formAction;
            dynamicForm.method = formMethod;

            if (!document || !document.body) {
                console.error('Submit form action: Document or body is not available');
                return false;
            }

            if (sourceForm && sourceForm.innerHTML) {
                dynamicForm.innerHTML = sourceForm.innerHTML;
            }

            document.body.appendChild(dynamicForm);

            if (typeof dynamicForm.submit === 'function') {
                dynamicForm.submit();
            } else {
                console.error('Submit form action: Form submit method is not available');
                return false;
            }

            if (document.body.contains(dynamicForm)) {
                document.body.removeChild(dynamicForm);
            }

            return true;
        };

        const success = createAndSubmitDynamicForm(formElement);

        if (!success) {
            console.error('Submit form action: Failed to submit form');
        }

        return of(void 0);
    }
}
