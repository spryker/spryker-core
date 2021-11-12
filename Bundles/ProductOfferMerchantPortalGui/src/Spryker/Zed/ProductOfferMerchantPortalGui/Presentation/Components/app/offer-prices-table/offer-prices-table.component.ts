import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-offer-prices-table',
    templateUrl: './offer-prices-table.component.html',
    styleUrls: ['./offer-prices-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-offer-prices-table',
    },
})
export class OfferPricesTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
