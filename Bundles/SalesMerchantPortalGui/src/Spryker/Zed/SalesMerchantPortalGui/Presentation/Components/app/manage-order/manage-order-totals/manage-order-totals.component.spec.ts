import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { CardModule } from '@spryker/card';

import { ManageOrderTotalsComponent } from './manage-order-totals.component';

describe('ManageOrderTotalsComponent', () => {
    let component: ManageOrderTotalsComponent;
    let fixture: ComponentFixture<ManageOrderTotalsComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ManageOrderTotalsComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ManageOrderTotalsComponent);
        component = fixture.componentInstance;
    });

    it('should render list of totals using @Input(orderTotals)', () => {
        const mockoOderTotals = [
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

        component.orderTotals = mockoOderTotals;
        fixture.detectChanges();

        const orderTotalElems = fixture.debugElement.queryAll(By.css('.mp-manage-order-totals__item'));

        expect(orderTotalElems.length).toBe(mockoOderTotals.length);

        orderTotalElems.forEach((orderTotalElem, i) => {
            const orderTotalTitleElem = fixture.debugElement.query(
                By.css(
                    `.mp-manage-order-totals__item:nth-child(${i + 1}) .mp-manage-order-totals__col:first-child`
                )
            );
            const orderValueTitleElem = fixture.debugElement.query(
                By.css(
                    `.mp-manage-order-totals__item:nth-child(${i + 1}) .mp-manage-order-totals__col:last-child`
                )
            );

            expect(orderTotalTitleElem.nativeElement.textContent).toBe(mockoOderTotals[i].title);
            expect(orderValueTitleElem.nativeElement.textContent).toBe(mockoOderTotals[i].value);
        });
    });

    it('should add `mp-manage-order-totals__item--title` class if @Input(orderTotals.isTitle) is true', () => {
        const mockoOderTotals = [
            {
                title: 'Subtotal',
                value: '€ 972.51',
                isTitle: true,
            },
        ];

        component.orderTotals = mockoOderTotals;
        fixture.detectChanges();

        const orderTotalElems = fixture.debugElement.query(By.css('.mp-manage-order-totals__item.mp-manage-order-totals__item--title'));

        expect(orderTotalElems).toBeTruthy();
    });
});
