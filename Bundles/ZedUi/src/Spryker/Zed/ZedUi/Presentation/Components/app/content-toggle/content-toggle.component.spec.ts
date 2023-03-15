import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ContentToggleComponent } from './content-toggle.component';

describe('ContentToggleComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ContentToggleComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span toggle-text></span>
            <div class="default-slot"></div>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-checkbox> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const checkboxComponent = host.queryCss('spy-checkbox');

        expect(checkboxComponent).toBeTruthy();
    });

    it('should render `toggle-text` slot to the <spy-checkbox> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const toggleTextSlot = host.queryCss('spy-checkbox [toggle-text]');

        expect(toggleTextSlot).toBeTruthy();
    });

    it('should render default slot to the `.mp-content-toggle__content` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.mp-content-toggle__content .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should bound `@Input(name)` to the `name` input of <spy-checkbox> component', async () => {
        const mockName = 'mockName';
        const host = await createComponentWrapper(createComponent, { name: mockName });
        const checkboxComponent = host.queryCss('spy-checkbox');

        expect(checkboxComponent.properties.name).toBe(mockName);
    });

    it('should bound `@Input(isContentHidden)` to the `checked` input of <spy-checkbox> component', async () => {
        const host = await createComponentWrapper(createComponent, { isContentHidden: true });
        const checkboxComponent = host.queryCss('spy-checkbox');

        expect(checkboxComponent.properties.checked).toBe(true);
    });

    it('should bound `@Input(isContentHidden)` to the `hidden` input of `.mp-content-toggle__content` element', async () => {
        const host = await createComponentWrapper(createComponent, { isContentHidden: true });
        const contentElem = host.queryCss('.mp-content-toggle__content');

        expect(contentElem.properties.hidden).toBe(true);
    });

    it('should change `hidden` property of `.mp-content-toggle__content` element by <spy-checkbox> component change', async () => {
        const host = await createComponentWrapper(createComponent, { isContentHidden: false });
        const contentElem = host.queryCss('.mp-content-toggle__content');
        const checkboxComponent = host.queryCss('spy-checkbox');

        expect(contentElem.properties.hidden).toBe(false);

        checkboxComponent.triggerEventHandler('checkedChange', true);
        host.detectChanges();

        expect(contentElem.properties.hidden).toBe(true);
    });
});
