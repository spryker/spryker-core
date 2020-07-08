import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-offer-orders-table',
    templateUrl: './offer-orders-table.component.html',
    styleUrls: ['./offer-orders-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class OfferOrdersTableComponent {
    @Input() config: TableConfig;
}
