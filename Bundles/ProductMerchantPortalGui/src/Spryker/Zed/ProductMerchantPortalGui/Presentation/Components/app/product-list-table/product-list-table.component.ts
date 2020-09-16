import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-product-list-table',
    templateUrl: './product-list-table.component.html',
    styleUrls: ['./product-list-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class ProductListTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
