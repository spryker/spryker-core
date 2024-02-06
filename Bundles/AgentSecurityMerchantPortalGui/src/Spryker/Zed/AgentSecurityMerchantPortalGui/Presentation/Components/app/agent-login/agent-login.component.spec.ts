import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { CardModule } from '@spryker/card';
import { LogoModule } from '@spryker/logo';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { AgentLoginComponent } from './agent-login.component';

describe('AgentLoginComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(AgentLoginComponent, {
        ngModule: {
            imports: [CardModule, LogoModule],
            schemas: [NO_ERRORS_SCHEMA],
        },
        projectContent: `
            <span title></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-logo> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const logoComponent = host.queryCss('spy-logo');

        expect(logoComponent).toBeTruthy();
    });

    it('should render <spy-card> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const cardComponent = host.queryCss('spy-card');

        expect(cardComponent).toBeTruthy();
    });

    it('should render `title` slot to the `.ant-card-head-title` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const titleSlot = host.queryCss('.ant-card-head-title [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render default slot to the `.ant-card-body` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.ant-card-body .default-slot');

        expect(defaultSlot).toBeTruthy();
    });
});
