import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-product-list',
    templateUrl: './product-list.component.html',
    styleUrls: ['./product-list.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class ProductListComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;
}
