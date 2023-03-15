import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { EditAbstractProductComponent } from './edit-abstract-product.component';

const mockProduct = {
    name: 'test name',
    sku: 'test sku',
};

describe('EditAbstractProductComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(EditAbstractProductComponent, {
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
        const host = await createComponentWrapper(createComponent, { product: mockProduct });
        const headlineComponent = host.queryCss('spy-headline');

        expect(headlineComponent).toBeTruthy();
    });

    it('should render `title` slot to the `.mp-edit-abstract-product__title` element', async () => {
        const host = await createComponentWrapper(createComponent, { product: mockProduct });
        const titleSlot = host.queryCss('.mp-edit-abstract-product__title [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render `action` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent, { product: mockProduct });
        const actionSlot = host.queryCss('spy-headline [action]');

        expect(actionSlot).toBeTruthy();
    });

    it('should render default slot to the `.mp-edit-abstract-product__content` element', async () => {
        const host = await createComponentWrapper(createComponent, { product: mockProduct });
        const defaultSlot = host.queryCss('.mp-edit-abstract-product__content .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should render `@Input(product)` data to the `.mp-edit-abstract-product__sub-title` element', async () => {
        const host = await createComponentWrapper(createComponent, { product: mockProduct });
        const subTitleElem = host.queryCss('.mp-edit-abstract-product__sub-title');

        expect(subTitleElem.nativeElement.textContent).toContain(`${mockProduct.sku}, ${mockProduct.name}`);
    });
});
