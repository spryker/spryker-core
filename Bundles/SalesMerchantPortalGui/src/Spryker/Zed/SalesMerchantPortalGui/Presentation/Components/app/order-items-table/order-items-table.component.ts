import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-order-items-table',
    templateUrl: './order-items-table.component.html',
    styleUrls: ['./order-items-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-order-items-table',
    },
})
export class OrderItemsTableComponent {
    @Input() config: TableConfig;
}
