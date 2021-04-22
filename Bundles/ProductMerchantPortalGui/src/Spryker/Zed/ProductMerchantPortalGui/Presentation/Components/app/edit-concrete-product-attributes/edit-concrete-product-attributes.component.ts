import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';
import { ToJson } from '@spryker/utils';

@Component({
    selector: 'mp-edit-concrete-product-attributes',
    templateUrl: './edit-concrete-product-attributes.component.html',
    styleUrls: ['./edit-concrete-product-attributes.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class EditConcreteProductAttributesComponent {
    @Input() @ToJson() tableConfig: TableConfig;
    @Input() tableId?: string;
}
