import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { MerchantRelationshipComponent } from './merchant-relationship.component';

describe('MerchantRelationshipComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(MerchantRelationshipComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
            <span button-action></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <mp-merchant-relationship-table> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const productListTableComponent = host.queryCss('mp-merchant-relationship-table');

        expect(productListTableComponent).toBeTruthy();
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

    it('should render `button-action` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const buttonActionSlot = host.queryCss('spy-headline [button-action]');

        expect(buttonActionSlot).toBeTruthy();
    });

    it('should bound `@Input(tableConfig)` to the `config` input of <mp-merchant-relationship-table> component', async () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        };
        const host = await createComponentWrapper(createComponent, { tableConfig: mockTableConfig });
        const productListTableComponent = host.queryCss('mp-merchant-relationship-table');

        expect(productListTableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bound `@Input(tableId)` to the `tableId` input of <mp-merchant-relationship-table> component', async () => {
        const mockTableId = 'mockTableId';
        const host = await createComponentWrapper(createComponent, { tableId: mockTableId });
        const productListTableComponent = host.queryCss('mp-merchant-relationship-table');

        expect(productListTableComponent.properties.tableId).toEqual(mockTableId);
    });
});
