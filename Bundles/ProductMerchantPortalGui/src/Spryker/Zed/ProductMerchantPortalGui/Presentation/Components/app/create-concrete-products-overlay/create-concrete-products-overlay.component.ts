import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';
import { ToJson } from '@spryker/utils';

interface ProductDetails {
    name: string;
    sku: string;
}

@Component({
    selector: 'mp-create-concrete-products-overlay',
    templateUrl: './create-concrete-products-overlay.component.html',
    styleUrls: ['./create-concrete-products-overlay.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-create-concrete-products-overlay',
    },
})
export class CreateConcreteProductsOverlayComponent {
    @Input() @ToJson() product?: ProductDetails;
}
