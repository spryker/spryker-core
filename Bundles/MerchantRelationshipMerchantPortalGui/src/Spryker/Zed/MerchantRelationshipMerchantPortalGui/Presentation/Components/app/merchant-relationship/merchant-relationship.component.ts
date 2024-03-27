import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-merchant-relationship',
    templateUrl: './merchant-relationship.component.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class MerchantRelationshipComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;
}
