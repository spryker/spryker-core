import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { EditOfferComponent } from './edit-offer.component';

describe('EditOfferComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(EditOfferComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
            <span sub-title></span>
            <span approval-status></span>
            <span action></span>
            <span product-status></span>
            <span product-details></span>
            <div class="default-slot"></div>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <mp-edit-offer> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const editOfferComponent = host.queryCss('mp-edit-offer');

        expect(editOfferComponent).toBeTruthy();
    });

    it('should render <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const headlineComponent = host.queryCss('spy-headline');

        expect(headlineComponent).toBeTruthy();
    });

    it('should render <spy-card> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const cardComponent = host.queryCss('spy-card');

        expect(cardComponent).toBeTruthy();
    });

    it('should render <mp-image-slider> component to the <spy-card> component', async () => {
        const mockImages = [
            {
                src: 'mockImages',
                alt: 'mockImages',
            },
        ];
        const host = await createComponentWrapper(createComponent, { images: mockImages });
        const imageSliderComponent = host.queryCss('spy-card mp-image-slider');

        expect(imageSliderComponent).toBeTruthy();
    });

    it('should render <spy-collapsible> component to the <spy-card> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const collapsibleComponent = host.queryCss('spy-card spy-collapsible');

        expect(collapsibleComponent).toBeTruthy();
    });

    it('should render `title` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const titleSlot = host.queryCss('spy-headline [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render `sub-title` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const subTitleSlot = host.queryCss('spy-headline [sub-title]');

        expect(subTitleSlot).toBeTruthy();
    });

    it('should render `approval-status` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const approvalStatusSlot = host.queryCss('spy-headline [approval-status]');

        expect(approvalStatusSlot).toBeTruthy();
    });

    it('should render `action` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const actionSlot = host.queryCss('spy-headline [action]');

        expect(actionSlot).toBeTruthy();
    });

    it('should render `product-status` slot to the <spy-card> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const productStatusSlot = host.queryCss('spy-card [product-status]');

        expect(productStatusSlot).toBeTruthy();
    });

    it('should render `product-details` slot to the <spy-collapsible> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const productDetailsSlot = host.queryCss('spy-collapsible [product-details]');

        expect(productDetailsSlot).toBeTruthy();
    });

    it('should render default slot to the <mp-edit-offer> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('mp-edit-offer .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should bound `@Input(productDetailsTitle)` to the `title` input of <spy-collapsible> component', async () => {
        const mockProductDetailsTitle = 'productDetailsTitle';
        const host = await createComponentWrapper(createComponent, { productDetailsTitle: mockProductDetailsTitle });
        const collapsibleComponent = host.queryCss('spy-collapsible');

        expect(collapsibleComponent.properties.spyTitle).toBe(mockProductDetailsTitle);
    });

    it('should bound `@Input(images)` to the `images` input of <mp-image-slider> component', async () => {
        const mockImages = [
            {
                src: 'mockImages',
                alt: 'mockImages',
            },
        ];
        const host = await createComponentWrapper(createComponent, { images: mockImages });
        const imageSliderComponent = host.queryCss('mp-image-slider');

        expect(imageSliderComponent.properties.images).toEqual(mockImages);
    });

    it('should render `@Input(product)` data to the appropriate places', async () => {
        const mockProduct = {
            name: 'name',
            sku: 'sku',
            validFrom: 'validFrom',
            validTo: 'validTo',
            validFromTitle: 'validFromTitle',
            validToTitle: 'validToTitle',
        };
        const host = await createComponentWrapper(createComponent, { product: mockProduct });
        const productTitleElem = host.queryCss('.mp-edit-offer__product-title');
        const productSkuElem = host.queryCss('.mp-edit-offer__product-sku');
        const validFromValueElem = host.queryCss(
            '.mp-edit-offer__product-dates-col:first-child .mp-edit-offer__product-dates-value',
        );
        const validToValueElem = host.queryCss(
            '.mp-edit-offer__product-dates-col:last-child .mp-edit-offer__product-dates-value',
        );
        const validFromTitleElem = host.queryCss(
            '.mp-edit-offer__product-dates-col:first-child .mp-edit-offer__product-dates-title',
        );
        const validToTitleElem = host.queryCss(
            '.mp-edit-offer__product-dates-col:last-child .mp-edit-offer__product-dates-title',
        );

        expect(productTitleElem.nativeElement.textContent).toContain(mockProduct.name);
        expect(productSkuElem.nativeElement.textContent).toContain(mockProduct.sku);
        expect(validFromValueElem.nativeElement.textContent).toContain(mockProduct.validFrom);
        expect(validToValueElem.nativeElement.textContent).toContain(mockProduct.validTo);
        expect(validFromTitleElem.nativeElement.textContent).toContain(mockProduct.validFromTitle);
        expect(validToTitleElem.nativeElement.textContent).toContain(mockProduct.validToTitle);
    });
});
