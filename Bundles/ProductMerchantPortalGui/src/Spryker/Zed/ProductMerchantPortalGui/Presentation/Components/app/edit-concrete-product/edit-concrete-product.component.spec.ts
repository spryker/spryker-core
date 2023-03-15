import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { EditConcreteProductComponent } from './edit-concrete-product.component';

describe('EditConcreteProductComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(EditConcreteProductComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
            <span name></span>
            <span action></span>
            <span sub-title></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const headlineComponent = host.queryCss('spy-headline');

        expect(headlineComponent).toBeTruthy();
    });

    it('should render `title` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const titleSlot = host.queryCss('spy-headline [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render `name` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const nameSlot = host.queryCss('spy-headline [name]');

        expect(nameSlot).toBeTruthy();
    });

    it('should render `action` to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const actionSlot = host.queryCss('spy-headline [action]');

        expect(actionSlot).toBeTruthy();
    });

    it('should render `sub-title` slot to the `.mp-edit-concrete-product__header` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const subTitleSlot = host.queryCss('.mp-edit-concrete-product__header [sub-title]');

        expect(subTitleSlot).toBeTruthy();
    });

    it('should render default slot to the `.mp-edit-concrete-product__content` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.mp-edit-concrete-product__content .default-slot');

        expect(defaultSlot).toBeTruthy();
    });
});
