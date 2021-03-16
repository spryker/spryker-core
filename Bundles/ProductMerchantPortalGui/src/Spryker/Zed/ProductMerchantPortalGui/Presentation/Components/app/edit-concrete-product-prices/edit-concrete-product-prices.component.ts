import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-edit-concrete-product-prices',
    templateUrl: './edit-concrete-product-prices.component.html',
    styleUrls: ['./edit-concrete-product-prices.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class EditConcreteProductPricesComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;

    isChecked: boolean = false;
}
