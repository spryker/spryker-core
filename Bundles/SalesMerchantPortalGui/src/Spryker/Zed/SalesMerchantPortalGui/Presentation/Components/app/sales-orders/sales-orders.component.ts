import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-sales-orders',
    templateUrl: './sales-orders.component.html',
    styleUrls: ['./sales-orders.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class SalesOrdersComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;
}
