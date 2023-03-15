import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { AutogenerateInputComponent } from './autogenerate-input.component';

describe('AutogenerateInputComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(AutogenerateInputComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `<span class="default-slot"></span>`,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-form-item> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const formItemComponent = host.queryCss('spy-form-item');

        expect(formItemComponent).toBeTruthy();
    });

    it('should render <spy-input> component to the `control` slot of <spy-form-item> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const inputComponent = host.queryCss('spy-form-item [control] spy-input');

        expect(inputComponent).toBeTruthy();
    });

    it('should render <spy-checkbox> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const checkboxComponent = host.queryCss('spy-checkbox');

        expect(checkboxComponent).toBeTruthy();
    });

    it('should render default slot to the <spy-checkbox> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('spy-checkbox .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should render hidden input if `@Input(isAutogenerate)` is true', async () => {
        const host = await createComponentWrapper(createComponent, { isAutogenerate: true });
        const hiddenInputElem = host.queryCss('input[type=hidden]');

        expect(hiddenInputElem).toBeTruthy();
    });

    it('should disable <spy-input> component when <spy-checkbox> component is checked', async () => {
        const host = await createComponentWrapper(createComponent, { isAutogenerate: false });
        const checkboxComponent = host.queryCss('spy-checkbox');
        const inputComponent = host.queryCss('spy-input');

        expect(inputComponent.properties.disabled).toBe(false);

        checkboxComponent.triggerEventHandler('checkedChange', true);
        host.detectChanges();

        expect(inputComponent.properties.disabled).toBe(true);
    });

    it('should change <spy-input> component value to initial `@Input(value)` when <spy-checkbox> component is checked', async () => {
        const mockValue = 'Value';
        const mockNewValue = 'NewValue';
        const host = await createComponentWrapper(createComponent, { value: mockValue });
        const checkboxComponent = host.queryCss('spy-checkbox');
        const inputComponent = host.queryCss('spy-input');

        inputComponent.triggerEventHandler('valueChange', mockNewValue);
        host.detectChanges();

        expect(inputComponent.properties.value).toBe(mockNewValue);

        checkboxComponent.triggerEventHandler('checkedChange', true);
        host.detectChanges();

        expect(inputComponent.properties.value).toBe(mockValue);
    });

    describe('@Inputs', () => {
        it('should bound `@Input(name)` to the `name` input of <spy-input> component', async () => {
            const mockName = 'Name';
            const host = await createComponentWrapper(createComponent, { name: mockName });
            const inputComponent = host.queryCss('spy-input');

            expect(inputComponent.properties.name).toBe(mockName);
        });

        it('should bound `@Input(value)` to the `value` input of <spy-input> component', async () => {
            const mockValue = 'Value';
            const host = await createComponentWrapper(createComponent, { value: mockValue });
            const inputComponent = host.queryCss('spy-input');

            expect(inputComponent.properties.value).toBe(mockValue);
        });

        it('should bound `@Input(placeholder)` to the `placeholder` input of <spy-input> component', async () => {
            const mockPlaceholder = 'Placeholder';
            const host = await createComponentWrapper(createComponent, { placeholder: mockPlaceholder });
            const inputComponent = host.queryCss('spy-input');

            expect(inputComponent.properties.placeholder).toBe(mockPlaceholder);
        });

        it('should bound `@Input(isAutogenerate)` to the `disabled` input of <spy-input> component', async () => {
            const host = await createComponentWrapper(createComponent, { isAutogenerate: true });
            const inputComponent = host.queryCss('spy-input');

            expect(inputComponent.properties.disabled).toBe(true);
        });

        it('should bound `@Input(isAutogenerate)` to the `checked` input of <spy-checkbox> component', async () => {
            const host = await createComponentWrapper(createComponent, { isAutogenerate: true });
            const checkboxComponent = host.queryCss('spy-checkbox');

            expect(checkboxComponent.properties.checked).toBe(true);
        });

        it('should bound `@Input(checkboxName)` to the `name` input of <spy-checkbox> component', async () => {
            const mockCheckboxName = 'checkboxName';
            const host = await createComponentWrapper(createComponent, { checkboxName: mockCheckboxName });
            const checkboxComponent = host.queryCss('spy-checkbox');

            expect(checkboxComponent.properties.name).toBe(mockCheckboxName);
        });

        it('should bound `@Input(error)` to the `error` input of <spy-form-item> component', async () => {
            const mockError = 'Error';
            const host = await createComponentWrapper(createComponent, { error: mockError });
            const formItemComponent = host.queryCss('spy-form-item');

            expect(formItemComponent.properties.error).toBe(mockError);
        });

        it('should add `mp-autogenerate-input--half-width` class to the component if `@Input(isFieldHasHalfWidth)` is true', async () => {
            const host = await createComponentWrapper(createComponent, { isFieldHasHalfWidth: true });
            const inputHalfWidthElem = host.queryCss('.mp-autogenerate-input.mp-autogenerate-input--half-width');

            expect(inputHalfWidthElem).toBeTruthy();
        });
    });
});
