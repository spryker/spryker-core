import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-create-abstract-product',
    templateUrl: './create-abstract-product.component.html',
    styleUrls: ['./create-abstract-product.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-create-abstract-product' },
})
export class CreateAbstractProductComponent {}
