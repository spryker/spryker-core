import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { DashboardStatsBlockComponent } from './dashboard-stats-block.component';

describe('DashboardStatsComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(DashboardStatsBlockComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render `@Input(count)` to the `.mp-dashboard-stats-block__text--count` element', async () => {
        const mockCount = 'count';
        const host = await createComponentWrapper(createComponent, { count: mockCount });
        const countContentElem = host.queryCss('.mp-dashboard-stats-block__text--count');

        expect(countContentElem.nativeElement.textContent).toContain(mockCount);
    });

    it('should render `@Input(name)` to the `.mp-dashboard-stats-block__text:last-child` element', async () => {
        const mockName = 'name';
        const host = await createComponentWrapper(createComponent, { name: mockName });
        const nameContentElem = host.queryCss('.mp-dashboard-stats-block__text:last-child');

        expect(nameContentElem.nativeElement.textContent).toContain(mockName);
    });
});
