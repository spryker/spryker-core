import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { FormComponent } from './form.component';

describe('FormComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(FormComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <form> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const formComponent = host.queryCss('form');

        expect(formComponent).toBeTruthy();
    });

    it('should bound `@Input(method)` to the `method` input of <form> component', async () => {
        const mockMethod = 'mockMethod';
        const host = await createComponentWrapper(createComponent, { method: mockMethod });
        const formComponent = host.queryCss('form');

        expect(formComponent.properties.method).toBe(mockMethod);
    });

    it('should bound `@Input(action)` to the `action` input of <form> component', async () => {
        const mockAction = 'mockAction';
        const host = await createComponentWrapper(createComponent, { action: mockAction });
        const formComponent = host.queryCss('form');

        expect(formComponent.properties.action).toBe(mockAction);
    });

    it('should bound `@Input(name)` to the `name` input of <form> component', async () => {
        const mockName = 'mockName';
        const host = await createComponentWrapper(createComponent, { name: mockName });
        const formComponent = host.queryCss('form');

        expect(formComponent.properties.name).toBe(mockName);
    });

    it('should bound `@Input(attrs)` to the `spyApplyAttrs` input of <form> component', async () => {
        const mockAttrs = { mock: 'mockValue' };
        const host = await createComponentWrapper(createComponent, { attrs: mockAttrs });
        const formComponent = host.queryCss('form');

        expect(formComponent.properties.spyApplyAttrs).toEqual(mockAttrs);
    });

    it('should bound `@Input(withMonitor)` to the `spyUnsavedChangesFormMonitor` input of <form> component', async () => {
        const mockMonitor = true;
        const host = await createComponentWrapper(createComponent, { withMonitor: mockMonitor });
        const formComponent = host.queryCss('form');

        expect(formComponent.properties.spyUnsavedChangesFormMonitor).toBe(mockMonitor);
    });
});
