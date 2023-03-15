import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ManageOrderTotalsComponent } from './manage-order-totals.component';

describe('ManageOrderTotalsComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ManageOrderTotalsComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render list of totals from `@Input(orderTotals)`', async () => {
        const mockOrderTotals = [
            {
                title: 'Subtotal',
                value: '€ 972.51',
                isTitle: true,
            },
            {
                title: 'Shipment Hermes Express',
                value: '€ 972.51',
            },
        ];
        const host = await createComponentWrapper(createComponent, { orderTotals: mockOrderTotals });
        const orderTotalElems = host.fixture.debugElement.queryAll(By.css('.mp-manage-order-totals__item'));

        expect(orderTotalElems.length).toBe(mockOrderTotals.length);

        orderTotalElems.forEach((orderTotalElem, i) => {
            const orderTotalTitleElem = host.queryCss(
                `.mp-manage-order-totals__item:nth-child(${i + 1}) .mp-manage-order-totals__col:first-child`,
            );
            const orderValueTitleElem = host.queryCss(
                `.mp-manage-order-totals__item:nth-child(${i + 1}) .mp-manage-order-totals__col:last-child`,
            );

            expect(orderTotalTitleElem.nativeElement.textContent).toBe(mockOrderTotals[i].title);
            expect(orderValueTitleElem.nativeElement.textContent).toBe(mockOrderTotals[i].value);
        });
    });

    it('should add `mp-manage-order-totals__item--title` class if `@Input(orderTotals.isTitle)` is true', async () => {
        const mockOrderTotals = [
            {
                title: 'Subtotal',
                value: '€ 972.51',
                isTitle: true,
            },
        ];
        const host = await createComponentWrapper(createComponent, { orderTotals: mockOrderTotals });
        const orderTotalTitleElem = host.queryCss('.mp-manage-order-totals__item.mp-manage-order-totals__item--title');

        expect(orderTotalTitleElem).toBeTruthy();
    });
});
