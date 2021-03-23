import { ChangeDetectionStrategy, Component, EventEmitter, Input, Output, ViewEncapsulation } from '@angular/core';
import { ConcreteProductPreview } from './types';
import { ProductAttribute } from '../product-attributes-selector/types';

@Component({
    selector: 'mp-concrete-products-preview',
    templateUrl: './concrete-products-preview.component.html',
    styleUrls: ['./concrete-products-preview.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-concrete-products-preview' },
})
export class ConcreteProductsPreviewComponent {
    @Input() attributes: ProductAttribute[];
    @Input() generatedProducts: ConcreteProductPreview[];
    @Input() name?: string;
    @Output() generatedProductsChange = new EventEmitter<ConcreteProductPreview[]>();
}
