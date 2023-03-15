import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ManageOrderStatsBlockComponent } from './manage-order-stats-block.component';

describe('ManageOrderStatsBlockComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ManageOrderStatsBlockComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render `@Input(name)` to the `.mp-manage-order-stats-block__text--name` element', async () => {
        const mockName = 'name';
        const host = await createComponentWrapper(createComponent, { name: mockName });
        const orderStatsBlockNameElem = host.queryCss('.mp-manage-order-stats-block__text--name');

        expect(orderStatsBlockNameElem.nativeElement.textContent).toContain(mockName);
    });

    it('should render `@Input(info)` to the `.mp-manage-order-stats-block__text:last-child` element', async () => {
        const mockInfo = 'info';
        const host = await createComponentWrapper(createComponent, { info: mockInfo });
        const orderStatsBlockInfoElem = host.queryCss('.mp-manage-order-stats-block__text:last-child');

        expect(orderStatsBlockInfoElem.nativeElement.textContent).toContain(mockInfo);
    });
});
