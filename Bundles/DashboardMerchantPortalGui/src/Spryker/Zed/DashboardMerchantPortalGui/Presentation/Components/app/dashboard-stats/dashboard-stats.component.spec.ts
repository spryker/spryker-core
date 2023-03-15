import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { CardModule } from '@spryker/card';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { DashboardStatsComponent } from './dashboard-stats.component';

describe('DashboardStatsComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(DashboardStatsComponent, {
        ngModule: {
            imports: [CardModule],
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
