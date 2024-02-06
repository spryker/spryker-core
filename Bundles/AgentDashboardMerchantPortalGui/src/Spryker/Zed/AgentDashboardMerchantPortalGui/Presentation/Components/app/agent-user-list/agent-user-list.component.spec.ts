import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';

import { AgentUserListComponent } from './agent-user-list.component';

describe('AgentUserListComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(AgentUserListComponent, {
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

    it('should render <mp-agent-user-list-table> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const agentUserListTableComponent = host.queryCss('mp-agent-user-list-table');

        expect(agentUserListTableComponent).toBeTruthy();
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

    it('should bound `@Input(tableConfig)` to the `config` input of <mp-agent-user-list-table> component', async () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        };
        const host = await createComponentWrapper(createComponent, { tableConfig: mockTableConfig });
        const agentUserListTableComponent = host.queryCss('mp-agent-user-list-table');

        expect(agentUserListTableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bound `@Input(tableId)` to the `tableId` input of <mp-agent-user-list-table> component', async () => {
        const mockTableId = 'mockTableId';
        const host = await createComponentWrapper(createComponent, { tableId: mockTableId });
        const agentUserListTableComponent = host.queryCss('mp-agent-user-list-table');

        expect(agentUserListTableComponent.properties.tableId).toEqual(mockTableId);
    });
});
