import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { OffersListComponent } from './offers-list.component';

describe('OffersListComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(OffersListComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
            <span action></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <mp-offers-list-table> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const offersListTableComponent = host.queryCss('mp-offers-list-table');

        expect(offersListTableComponent).toBeTruthy();
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

    it('should render `action` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const actionSlot = host.queryCss('spy-headline [action]');

        expect(actionSlot).toBeTruthy();
    });

    it('should bound `@Input(tableConfig)` to the `config` input of <mp-offers-list-table> component', async () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        };
        const host = await createComponentWrapper(createComponent, { tableConfig: mockTableConfig });
        const offersListTableComponent = host.queryCss('mp-offers-list-table');

        expect(offersListTableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bound `@Input(tableId)` to the `tableId` input of <mp-offers-list-table> component', async () => {
        const mockTableId = 'mockTableId';
        const host = await createComponentWrapper(createComponent, { tableId: mockTableId });
        const offersListTableComponent = host.queryCss('mp-offers-list-table');

        expect(offersListTableComponent.properties.tableId).toEqual(mockTableId);
    });
});
