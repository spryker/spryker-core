import {
    ChangeDetectionStrategy,
    Component,
    EventEmitter,
    Input,
    OnChanges,
    OnInit,
    Output,
    SimpleChanges,
    ViewEncapsulation,
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
export class ProductAttributesSelectorComponent implements OnChanges, OnInit {
    @Input() attributes: ProductAttribute[];
    @Input() selectedAttributes: ProductAttribute[];
    @Input() name?: string;
    @Output() selectedAttributesChange = new EventEmitter<ProductAttribute[]>();

    deleteIcon = IconDeleteModule.icon;
    attributesObject: Record<string, ProductAttribute> = {};
    attributeOptions: ProductAttributeValue[][] = [];

    ngOnInit(): void {
        this.create();
    }

    ngOnChanges(changes: SimpleChanges): void {
        if ('attributes' in changes) {
            this.generateAttributesObject();
        }
    }

    generateAttributesObject(): void {
        this.attributesObject = this.attributes.reduce((accum, attribute) => {
            return {
                ...accum,
                [attribute.value]: attribute,
            };
        }, {});
    }

    getAttributes(index: number): ProductAttributeValue[] {
        return this.attributeOptions[index];
    }

    superAttributeChange(value: string, index: number): void {
        const attribute = { ...this.attributesObject[value] };

        attribute.values = [];
        this.selectedAttributes = [...this.selectedAttributes];
        this.attributeOptions[index] = this.attributesObject[value]?.values;
        this.selectedAttributes[index] = attribute;
        this.disableSelectedAttributes();
        this.selectedAttributesChange.emit(this.selectedAttributes);
    }

    attributesChange(values: string[], index: number, selectedAttribute: ProductAttribute): void {
        const attribute = this.attributesObject[selectedAttribute.value];
        this.selectedAttributes = [...this.selectedAttributes];
        this.selectedAttributes[index].values = attribute.values.filter((value) => values.includes(value.value));
        this.selectedAttributesChange.emit(this.selectedAttributes);
    }

    disableSelectedAttributes(): void {
        this.attributes = this.attributes.map((attr) => ({
            ...attr,
            isDisabled: this.selectedAttributes.some((selectedAttr) => selectedAttr?.value === attr?.value),
        }));
    }

    create(): void {
        const emptyAttribute = {} as ProductAttribute;
        this.selectedAttributes = [...this.selectedAttributes, emptyAttribute];
    }

    delete(index: number): void {
        this.selectedAttributes = [...this.selectedAttributes.filter((item, itemIndex) => itemIndex !== index)];
        this.attributeOptions = [...this.attributeOptions.filter((item, itemIndex) => itemIndex !== index)];
        this.disableSelectedAttributes();
        this.selectedAttributesChange.emit(this.selectedAttributes);
    }
}
