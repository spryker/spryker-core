import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-merchant-relationship-table',
    templateUrl: './merchant-relationship-table.component.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class MerchantRelationshipTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
