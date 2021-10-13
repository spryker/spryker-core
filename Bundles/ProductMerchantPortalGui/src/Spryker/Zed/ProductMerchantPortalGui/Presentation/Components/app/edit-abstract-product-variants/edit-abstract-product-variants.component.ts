import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-edit-abstract-product-variants',
    templateUrl: './edit-abstract-product-variants.component.html',
    styleUrls: ['./edit-abstract-product-variants.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-edit-abstract-product-variants',
    },
})
export class EditAbstractProductVariantsComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
