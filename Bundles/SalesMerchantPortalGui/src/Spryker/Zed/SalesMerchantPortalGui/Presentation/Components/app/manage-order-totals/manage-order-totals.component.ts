import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';
import { ToJson } from '@spryker/utils';

export interface OrderTotals {
    title: string;
    value: string;
    isTitle?: boolean;
}

@Component({
    selector: 'mp-manage-order-totals',
    templateUrl: './manage-order-totals.component.html',
    styleUrls: ['./manage-order-totals.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class ManageOrderTotalsComponent {
    @Input() @ToJson() orderTotals: OrderTotals[];

    ngOnInit() {
        console.log(this.orderTotals)
    }
}
