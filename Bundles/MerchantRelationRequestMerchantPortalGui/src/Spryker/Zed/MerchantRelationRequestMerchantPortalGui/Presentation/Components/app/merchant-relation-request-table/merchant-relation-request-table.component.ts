import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-merchant-relation-request-table',
    templateUrl: './merchant-relation-request-table.component.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class MerchantRelationRequestTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
