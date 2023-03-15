import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ProductOfferComponent } from './product-offer.component';

describe('ProductOfferComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ProductOfferComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
            <span description></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <mp-product-offer-table> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const productOfferTableComponent = host.queryCss('mp-product-offer-table');

        expect(productOfferTableComponent).toBeTruthy();
    });

    it('should render <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const headlineComponent = host.queryCss('spy-headline');

        expect(headlineComponent).toBeTruthy();
    });

    it('should render `.mp-product-offer__description` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const descriptionElem = host.queryCss('.mp-product-offer__description');

        expect(descriptionElem).toBeTruthy();
    });

    it('should render `title` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const titleSlot = host.queryCss('spy-headline [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render `description` slot to the `.mp-product-offer__description` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const descriptionSlot = host.queryCss('.mp-product-offer__description [description]');

        expect(descriptionSlot).toBeTruthy();
    });

    it('should bound `@Input(tableConfig)` to the `config` input of <mp-product-offer-table> component', async () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        };
        const host = await createComponentWrapper(createComponent, { tableConfig: mockTableConfig });
        const productOfferTableComponent = host.queryCss('mp-product-offer-table');

        expect(productOfferTableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bound `@Input(tableId)` to the `tableId` input of <mp-product-offer-table> component', async () => {
        const mockTableId = 'mockTableId';
        const host = await createComponentWrapper(createComponent, { tableId: mockTableId });
        const productOfferTableComponent = host.queryCss('mp-product-offer-table');

        expect(productOfferTableComponent.properties.tableId).toEqual(mockTableId);
    });
});
