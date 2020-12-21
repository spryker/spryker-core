import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-edit-abstract-product-prices',
    templateUrl: './edit-abstract-product-prices.component.html',
    styleUrls: ['./edit-abstract-product-prices.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class EditAbstractProductPricesComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
