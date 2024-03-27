import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-merchant-relation-request',
    templateUrl: './merchant-relation-request.component.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class MerchantRelationRequestComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;
}
