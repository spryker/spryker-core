import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';
import { ToJson } from '@spryker/utils';
import {
    ConcreteProductPreview,
    ConcreteProductPreviewErrors,
    ProductAttribute,
    ProductAttributeError,
} from '../../services/types';

@Component({
    selector: 'mp-create-concrete-products',
    templateUrl: './create-concrete-products.component.html',
    styleUrls: ['./create-concrete-products.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-create-concrete-products',
    },
})
export class CreateConcreteProductsComponent {
    @Input() @ToJson() attributes: ProductAttribute[] = [];
    @Input() @ToJson() selectedAttributes: ProductAttribute[] = [];
    @Input() @ToJson() attributeErrors?: ProductAttributeError[];
    @Input() @ToJson() existingProducts?: ConcreteProductPreview[];
    @Input() @ToJson() generatedProducts?: ConcreteProductPreview[];
    @Input() @ToJson() generatedProductErrors?: ConcreteProductPreviewErrors[];
    @Input() productsName?: string;
    @Input() attributesName?: string;
    @Input() attributesPlaceholder?: string;
}
