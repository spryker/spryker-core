import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';
import { ToJson } from '@spryker/utils';

@Component({
    selector: 'mp-edit-concrete-product-prices',
    templateUrl: './edit-concrete-product-prices.component.html',
    styleUrls: ['./edit-concrete-product-prices.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-edit-concrete-product-prices' },
})
export class EditConcreteProductPricesComponent {
    @Input() @ToJson() tableConfig: TableConfig;
    @Input() tableId?: string;
}
