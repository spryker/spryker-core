import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-offer-orders',
    templateUrl: './offer-orders.component.html',
    styleUrls: ['./offer-orders.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class OfferOrdersComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId: string;
}
