import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-product-offer',
    templateUrl: './product-offer.component.html',
    styleUrls: ['./product-offer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-product-offer',
    },
})
export class ProductOfferComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;
}
