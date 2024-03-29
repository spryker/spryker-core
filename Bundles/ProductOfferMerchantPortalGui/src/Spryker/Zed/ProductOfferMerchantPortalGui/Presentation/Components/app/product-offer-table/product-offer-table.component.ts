import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-product-offer-table',
    templateUrl: './product-offer-table.component.html',
    styleUrls: ['./product-offer-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class ProductOfferTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
