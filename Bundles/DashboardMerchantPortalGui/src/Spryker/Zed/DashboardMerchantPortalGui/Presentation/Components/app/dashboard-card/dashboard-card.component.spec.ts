import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { CardModule } from '@spryker/card';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { DashboardCardComponent } from './dashboard-card.component';

describe('DashboardCardComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(DashboardCardComponent, {
        ngModule: {
            imports: [CardModule],
            schemas: [NO_ERRORS_SCHEMA],
        },
        projectContent: `
            <span title></span>
            <span actions></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render `title` slot to the `.ant-card-head-title` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const titleSlot = host.queryCss('.ant-card-head-title [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render `actions` slot to the `.ant-card-extra` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const actionsSlot = host.queryCss('.ant-card-extra [actions]');

        expect(actionsSlot).toBeTruthy();
    });

    it('should render default slot to the `.ant-card-body` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.ant-card-body .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should render <spy-chips> component to the `.ant-card-head-title` element if `@Input(count)` has bound', async () => {
        const mockCount = '5';
        const host = await createComponentWrapper(createComponent, { count: mockCount });
        const chipsComponent = host.queryCss('.ant-card-head-title spy-chips');

        expect(chipsComponent).toBeTruthy();
        expect(chipsComponent.nativeElement.textContent).toContain(mockCount);
    });
});
