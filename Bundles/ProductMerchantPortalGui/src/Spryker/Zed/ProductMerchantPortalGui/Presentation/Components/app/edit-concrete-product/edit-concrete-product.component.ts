import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-edit-concrete-product',
    templateUrl: './edit-concrete-product.component.html',
    styleUrls: ['./edit-concrete-product.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-edit-concrete-product' }
})
export class EditConcreteProductComponent {

}
