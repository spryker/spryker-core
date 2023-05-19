import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { CreateAbstractProductConcretesListComponent } from './create-abstract-product-concretes-list.component';

const mockForm = {
    notificationMessage: 'Test notification message',
    errorMessage: 'Test error',
    value: '',
    name: 'test_name',
    choices: [
        {
            label: 'Test 1',
            value: '1',
            hasNotificationMessage: true,
            hasError: true,
        },
        {
            label: 'Test 2',
            value: '0',
            hasNotificationMessage: false,
            hasError: true,
        },
    ],
};

describe('CreateAbstractProductConcretesListComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(CreateAbstractProductConcretesListComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `<span class="default-slot"></span>`,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-form-item> component', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const formItemComponent = host.queryCss('spy-form-item');

        expect(formItemComponent).toBeTruthy();
    });

    it('should render <spy-radio-group> component', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const radioGroupComponent = host.queryCss('spy-radio-group');

        expect(radioGroupComponent).toBeTruthy();
    });

    it('should render <spy-radio> components', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const radioComponents = host.fixture.debugElement.queryAll(By.css('spy-radio-group spy-radio'));

        expect(radioComponents).toBeTruthy();
        expect(radioComponents.length).toBe(mockForm.choices.length);
    });

    it('should render `.mp-create-abstract-product-concretes-list__notification` element', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const radioGroupComponent = host.queryCss('spy-radio-group');

        radioGroupComponent.triggerEventHandler('selected', mockForm.choices[0].value);
        host.detectChanges();

        const notificationElement = host.queryCss('.mp-create-abstract-product-concretes-list__notification');
        const notificationIconElement = host.queryCss('.mp-create-abstract-product-concretes-list__notification-icon');
        const notificationMessageElement = host.queryCss(
            '.mp-create-abstract-product-concretes-list__notification-message',
        );

        expect(notificationElement).toBeTruthy();
        expect(notificationIconElement).toBeTruthy();
        expect(notificationMessageElement).toBeTruthy();
        expect(notificationMessageElement.nativeElement.textContent.trim()).toBe(mockForm.notificationMessage);
    });

    it('should render default slot', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const defaultSlot = host.queryCss('.default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should toggle `.mp-create-abstract-product-concretes-list__notification` element visibility by <spy-radio> change event', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const radioGroupComponent = host.queryCss('spy-radio-group');

        radioGroupComponent.triggerEventHandler('selected', mockForm.choices[0].value);
        host.detectChanges();

        const visibleNotificationElement = host.queryCss('.mp-create-abstract-product-concretes-list__notification');

        expect(visibleNotificationElement).toBeTruthy();

        radioGroupComponent.triggerEventHandler('selected', mockForm.choices[1].value);
        host.detectChanges();

        const hiddenNotificationElement = host.queryCss('.mp-create-abstract-product-concretes-list__notification');

        expect(hiddenNotificationElement).toBeFalsy();
    });

    it('should show `.mp-create-abstract-product-concretes-list__notification` element on init if <spy-radio> is active and `hasNotificationMessage` is true', async () => {
        const mockUpdatedForm = {
            notificationMessage: mockForm.notificationMessage,
            name: mockForm.name,
            value: '1',
            choices: [...mockForm.choices],
        };
        const host = await createComponentWrapper(createComponent, { form: mockUpdatedForm });
        const notificationElement = host.queryCss('.mp-create-abstract-product-concretes-list__notification');

        expect(notificationElement).toBeTruthy();
    });

    it('should bound `errorMessage` to the `error` input of <spy-form-item> component', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const formItemComponent = host.queryCss('spy-form-item');

        expect(formItemComponent.properties.error).toBe(mockForm.errorMessage);
    });

    it('should bound `value` and `name` to the appropriate inputs of <spy-radio-group> component', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const radioGroupComponent = host.queryCss('spy-radio-group');

        expect(radioGroupComponent.properties.value).toBe(mockForm.value);
        expect(radioGroupComponent.properties.name).toBe(mockForm.name);
    });

    it('should bound `value` and `hasError` to the appropriate inputs of <spy-radio> components', async () => {
        const host = await createComponentWrapper(createComponent, { form: mockForm });
        const radioComponents = host.fixture.debugElement.queryAll(By.css('spy-radio-group spy-radio'));

        expect(radioComponents[0].properties.value).toBe(mockForm.choices[0].value);
        expect(radioComponents[0].properties.hasError).toBe(mockForm.choices[0].hasError);
        expect(radioComponents[0].nativeElement.textContent.trim()).toBe(mockForm.choices[0].label);

        expect(radioComponents[1].properties.value).toBe(mockForm.choices[1].value);
        expect(radioComponents[1].properties.hasError).toBe(mockForm.choices[1].hasError);
        expect(radioComponents[1].nativeElement.textContent.trim()).toBe(mockForm.choices[1].label);
    });
});
