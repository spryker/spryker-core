import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';

import { AgentBarComponent } from './agent-bar.component';

describe('AgentBarComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(AgentBarComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span class="default-slot"></span>
            <span actions></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <mp-agent-bar> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const agentBarComponent = host.queryCss('mp-agent-bar');

        expect(agentBarComponent).toBeTruthy();
    });

    it('should render `default` slot to the `.mp-agent-bar__content` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.mp-agent-bar__content .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should render `actions` slot to the `.mp-agent-bar__actions` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const actionsSlot = host.queryCss('.mp-agent-bar__actions [actions]');

        expect(actionsSlot).toBeTruthy();
    });
});
