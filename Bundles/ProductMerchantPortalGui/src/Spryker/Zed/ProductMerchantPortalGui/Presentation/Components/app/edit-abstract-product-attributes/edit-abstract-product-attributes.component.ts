import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-edit-abstract-product-attributes',
    templateUrl: './edit-abstract-product-attributes.component.html',
    styleUrls: ['./edit-abstract-product-attributes.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class EditAbstractProductAttributesComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
