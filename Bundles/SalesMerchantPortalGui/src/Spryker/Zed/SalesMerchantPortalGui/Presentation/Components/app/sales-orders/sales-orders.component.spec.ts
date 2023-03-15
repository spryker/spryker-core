import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { SalesOrdersComponent } from './sales-orders.component';

describe('SalesOrdersComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(SalesOrdersComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <mp-sales-orders-table> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const salesOrdersTableComponent = host.queryCss('mp-sales-orders-table');

        expect(salesOrdersTableComponent).toBeTruthy();
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

    it('should bound `@Input(tableConfig)` to the `config` input of <mp-sales-orders-table> component', async () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        };
        const host = await createComponentWrapper(createComponent, { tableConfig: mockTableConfig });
        const salesOrdersTableComponent = host.queryCss('mp-sales-orders-table');

        expect(salesOrdersTableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bound `@Input(tableId)` to the `tableId` input of <mp-sales-orders-table> component', async () => {
        const mockTableId = 'mockTableId';
        const host = await createComponentWrapper(createComponent, { tableId: mockTableId });
        const salesOrdersTableComponent = host.queryCss('mp-sales-orders-table');

        expect(salesOrdersTableComponent.properties.tableId).toEqual(mockTableId);
    });
});
