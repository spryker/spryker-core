import { ComponentInputs } from '@orchestrator/ngx-testing';

/* eslint-disable @typescript-eslint/no-explicit-any */
export const createComponentWrapper = <T extends (...args: any) => any>(
    createComponent: T,
    inputs?: ComponentInputs<any>,
    detectChanges = true,
): ReturnType<T> => createComponent(inputs, detectChanges);
/* eslint-enable */

export { getTestingForComponent } from '@orchestrator/ngx-testing';
