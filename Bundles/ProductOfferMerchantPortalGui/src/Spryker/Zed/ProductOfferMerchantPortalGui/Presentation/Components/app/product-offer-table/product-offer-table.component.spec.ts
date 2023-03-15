import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ProductOfferTableComponent } from './product-offer-table.component';

describe('ProductOfferTableComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ProductOfferTableComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-table> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const tableComponent = host.queryCss('spy-table');

        expect(tableComponent).toBeTruthy();
    });

    it('should bound `@Input(config)` to the `config` input of <spy-table> component', async () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        };
        const host = await createComponentWrapper(createComponent, { config: mockTableConfig });
        const tableComponent = host.queryCss('spy-table');

        expect(tableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bound `@Input(tableId)` to the `tableId` input of <spy-table> component', async () => {
        const mockTableId = 'mockTableId';
        const host = await createComponentWrapper(createComponent, { tableId: mockTableId });
        const tableComponent = host.queryCss('spy-table');

        expect(tableComponent.properties.tableId).toEqual(mockTableId);
    });
});
