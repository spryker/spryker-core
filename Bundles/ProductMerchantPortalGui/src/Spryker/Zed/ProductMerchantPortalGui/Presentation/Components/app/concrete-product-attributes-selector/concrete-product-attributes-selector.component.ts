import {
    ChangeDetectionStrategy,
    Component,
    EventEmitter,
    Input,
    OnChanges,
    Output,
    SimpleChanges,
    ViewEncapsulation,
} from '@angular/core';
import { ProductAttribute, ProductAttributeValue, AttributeOptions, ProductAttributeError } from '../../services/types';
import { ToJson } from '@spryker/utils';

@Component({
    selector: 'mp-concrete-product-attributes-selector',
    templateUrl: './concrete-product-attributes-selector.component.html',
    styleUrls: ['./concrete-product-attributes-selector.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-concrete-product-attributes-selector' },
})
export class ConcreteProductAttributesSelectorComponent implements OnChanges {
    @Input() @ToJson() attributes: ProductAttribute[] = [];
    @Input() @ToJson() selectedAttributes: ProductAttribute[] = [];
    @Input() @ToJson() errors?: ProductAttributeError[];
    @Input() name?: string;
    @Input() placeholder?: string;
    @Output() selectedAttributesChange = new EventEmitter<ProductAttribute[]>();

    ngOnChanges(changes: SimpleChanges) {
        if ('attributes' in changes) {
            this.selectedAttributes = this.attributes.map((attribute, index) => ({
                ...attribute,
                attributes:
                    this.selectedAttributes[index]?.attributes.length > 0
                        ? this.selectedAttributes[index].attributes
                        : [],
            }));

            this.selectedAttributesChange.emit(this.selectedAttributes);
        }
    }

    getAttributes(attributes: ProductAttributeValue[]): AttributeOptions[] {
        return attributes.map((attribute) => ({
            title: attribute.name,
            value: attribute.value,
        }));
    }

    getSelectedAttributes(selectedAttributes: ProductAttribute[], index: number): string[] {
        if (!selectedAttributes.length || !selectedAttributes[index]?.attributes) {
            return [];
        }

        return selectedAttributes[index].attributes.map((attribute) => attribute.value);
    }

    updateSelectedAttributes(values: string[], attribute: ProductAttribute, index: number): void {
        this.selectedAttributes = [...this.selectedAttributes];
        this.selectedAttributes[index] = {
            name: attribute.name,
            value: attribute.value,
            attributes: attribute.attributes.filter((item) => values.includes(item.value)),
        };

        if (this.errors?.length && this.errors[index]) {
            this.errors = [...this.errors];
            delete this.errors[index];
        }

        this.selectedAttributesChange.emit(this.selectedAttributes);
    }

    getAttributeError(index: number, errors: ProductAttributeError[]): string | undefined {
        return errors?.[index]?.error;
    }
}
