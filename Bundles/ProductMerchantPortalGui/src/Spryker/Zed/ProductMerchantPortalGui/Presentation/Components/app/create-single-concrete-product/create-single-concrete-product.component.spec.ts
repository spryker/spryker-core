import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { CreateSingleConcreteProductComponent } from './create-single-concrete-product.component';

describe('CreateSingleConcreteProductComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(CreateSingleConcreteProductComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
            <span action></span>
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

    it('should render `title` slot to the `.mp-create-single-concrete-product__header` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const titleSlot = host.queryCss('.mp-create-single-concrete-product__header [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render `action` slot to the `.mp-create-single-concrete-product__header` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const actionSlot = host.queryCss('.mp-create-single-concrete-product__header [action]');

        expect(actionSlot).toBeTruthy();
    });

    it('should render default slot to the `.mp-create-single-concrete-product__content` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.mp-create-single-concrete-product__content .default-slot');

        expect(defaultSlot).toBeTruthy();
    });
});
