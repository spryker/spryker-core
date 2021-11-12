import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';
import { Level } from '@spryker/headline';

@Component({
    selector: 'mp-create-single-concrete-product',
    templateUrl: './create-single-concrete-product.component.html',
    styleUrls: ['./create-single-concrete-product.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-create-single-concrete-product' },
})
export class CreateSingleConcreteProductComponent {
    titleLevel = Level.H5;
}
