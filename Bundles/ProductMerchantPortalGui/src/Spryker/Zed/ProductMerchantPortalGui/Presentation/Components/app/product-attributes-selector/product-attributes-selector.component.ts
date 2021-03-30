import {
    ChangeDetectionStrategy,
    Component,
    EventEmitter,
    Input,
    OnChanges,
    Output, SimpleChanges,
    ViewEncapsulation
} from '@angular/core';
import { ProductAttribute, ProductAttributeValue } from './types';
import { IconDeleteModule } from '../../icons';

@Component({
    selector: 'mp-product-attributes-selector',
    templateUrl: './product-attributes-selector.component.html',
    styleUrls: ['./product-attributes-selector.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-product-attributes-selector' },
})
export class ProductAttributesSelectorComponent implements OnChanges {
    @Input() attributes: ProductAttribute[];
    @Input() selectedAttributes: ProductAttribute[];
    @Input() name?: string;
    @Output() selectedAttributesChange = new EventEmitter<ProductAttribute[]>();

    deleteIcon = IconDeleteModule.icon;
    attributesObject: Record<string, ProductAttribute> = {};
    attributeOptions: ProductAttributeValue[][] = [];

    ngOnChanges(changes: SimpleChanges): void {
        if ('attributes' in changes) {
            this.attributesObject = this.attributes.reduce((accum, attribute) => {
                return {
                    ...accum,
                    [attribute.value]: attribute,
                }
            }, {});
        }
    }

    getAttributes(index: number): ProductAttributeValue[] {
        return this.attributeOptions[index];
    }

    superAttributeChange(value: string, index: number): void {
        const attribute = { ...this.attributesObject[value] };

        attribute.values = [];
        this.selectedAttributes = [...this.selectedAttributes];
        this.attributeOptions[index] = this.attributesObject[value].values;
        this.selectedAttributes[index] = attribute;
        this.selectedAttributesChange.emit(this.selectedAttributes);
    }

    attributesChange(values: string[], index: number, selectedAttribute: ProductAttribute): void {
        const attribute = this.attributesObject[selectedAttribute.value];
        this.selectedAttributes = [...this.selectedAttributes];
        this.selectedAttributes[index].values = attribute.values.filter(value => values.includes(value.value));
        this.selectedAttributesChange.emit(this.selectedAttributes);
    }

    create(): void {
        this.selectedAttributes = [...this.selectedAttributes, null];
    }

    delete(): void {}
}
