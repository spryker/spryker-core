import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { ToJson } from '@spryker/utils';
import { ConcreteProductPreview, ConcreteProductPreviewErrors, ProductAttribute } from '../../services/types';
import { Level } from '@spryker/headline';

@Component({
    selector: 'mp-create-multi-concrete-product',
    templateUrl: './create-multi-concrete-product.component.html',
    styleUrls: ['./create-multi-concrete-product.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-create-multi-concrete-product' },
})
export class CreateMultiConcreteProductComponent {
    @Input() @ToJson() attributes: ProductAttribute[] = [];
    @Input() @ToJson() selectedAttributes?: ProductAttribute[];
    @Input() @ToJson() generatedProducts?: ConcreteProductPreview[];
    @Input() @ToJson() generatedProductErrors?: ConcreteProductPreviewErrors[];
    @Input() productsName = '';
    @Input() attributesName = '';
    @Input() attributesPlaceholder = '';
    @Input() valuesPlaceholder = '';
    @Input() skuPlaceholder = '';
    @Input() namePlaceholder = '';
    titleLevel = Level.H5;
}
