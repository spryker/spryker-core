import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-sales-orders-table',
    templateUrl: './sales-orders-table.component.html',
    styleUrls: ['./sales-orders-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class SalesOrdersTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
