import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';
import { ToJson } from '@spryker/utils';

export interface OrderDetails {
    title: string;
    reference: string;
    referenceTitle: string;
}

@Component({
    selector: 'mp-manage-order',
    templateUrl: './manage-order.component.html',
    styleUrls: ['./manage-order.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-manage-order',
    },
})
export class ManageOrderComponent {
    @Input() @ToJson() orderDetails: OrderDetails = {
        title: 'Order DE — 1',
        reference: 'Mer - DE —134353',
        referenceTitle: 'Merchant Reference',
    };
}
