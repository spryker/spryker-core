import {
    ChangeDetectionStrategy,
    ChangeDetectorRef,
    Component,
    EventEmitter,
    Input,
    OnChanges,
    OnInit,
    Output,
    SimpleChanges,
    ViewEncapsulation,
} from '@angular/core';
import { ToJson } from '@spryker/utils';
import { ButtonSize, ButtonVariant } from '@spryker/button';
import { AttributeOptions, ProductAttribute } from '../../services/types';
import { IconDeleteModule } from '../../icons';
import { IconPlusModule } from '@spryker/icon/icons';

@Component({
    selector: 'mp-product-attributes-selector',
    templateUrl: './product-attributes-selector.component.html',
    styleUrls: ['./product-attributes-selector.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-product-attributes-selector' },
})
export class ProductAttributesSelectorComponent implements OnChanges, OnInit {
    @Input() @ToJson() attributes: ProductAttribute[] = [];
    @Input() @ToJson() selectedAttributes: ProductAttribute[] = [];
    @Input() name?: string;
    @Input() attributesPlaceholder?: string;
    @Input() valuesPlaceholder?: string;
    @Output() selectedAttributesChange = new EventEmitter<ProductAttribute[]>();

    deleteIcon = IconDeleteModule.icon;
    addIcon = IconPlusModule.icon;
    superAttributeOptions: AttributeOptions[] = [];
    attributeOptions: AttributeOptions[][] = [];
    addProductAttributesButtonVariant = ButtonVariant.Outline;
    addProductAttributesButtonSize = ButtonSize.Small;
    private attributesObject: Record<string, ProductAttribute> = {};

    constructor(private cdr: ChangeDetectorRef) {}

    ngOnInit(): void {
        if (!this.selectedAttributes.length) {
            this.create();
        }
    }

    ngOnChanges(changes: SimpleChanges): void {
        if ('attributes' in changes) {
            this.disableSelectedAttributes();
            this.generateAttributesObject();
        }

        if ('selectedAttributes' in changes) {
            this.initAttributeOptions();

            if (!changes.selectedAttributes.firstChange && !this.selectedAttributes.length) {
                this.disableSelectedAttributes();
                this.create();
            }
        }
    }

    private generateAttributesObject(): void {
        this.attributesObject = this.attributes.reduce(
            (accum, attribute) => ({
                ...accum,
                [attribute.value]: attribute,
            }),
            {},
        );
    }

    private initAttributeOptions(): void {
        this.attributeOptions = this.selectedAttributes.map((attrs) =>
            this.attributesObject[attrs.value]?.attributes.map((attr) => ({
                title: attr.name,
                value: attr.value,
            })),
        );
    }

    private remapSuperAttributes(): void {
        this.superAttributeOptions = this.attributes.map((attr) => ({
            title: attr.name,
            value: attr.value,
            isDisabled: attr.isDisabled,
        }));
    }

    private disableSelectedAttributes(): void {
        this.attributes = this.attributes.map((attr) => ({
            ...attr,
            isDisabled: this.selectedAttributes.some((selectedAttr) => selectedAttr?.value === attr?.value),
        }));
        this.remapSuperAttributes();
    }

    getAttributes(index: number, attributeOptions: AttributeOptions[][]): AttributeOptions[] {
        return attributeOptions[index];
    }

    getSelectedAttributes(index: number, selectedAttributes: ProductAttribute[]): string[] {
        if (selectedAttributes[index]?.attributes) {
            return selectedAttributes[index].attributes.map((attr) => attr.value);
        }
    }

    superAttributeChange(value: string, index: number): void {
        const superAttribute = { ...this.attributesObject[value] };

        superAttribute.attributes = [];
        this.selectedAttributes = [...this.selectedAttributes];
        this.attributeOptions[index] = this.attributesObject[value]?.attributes.map((attr) => ({
            title: attr.name,
            value: attr.value,
        }));
        this.selectedAttributes[index] = superAttribute;
        this.disableSelectedAttributes();
        this.selectedAttributesChange.emit(this.selectedAttributes);
        this.cdr.markForCheck();
    }

    attributesChange(values: string[], index: number, selectedAttribute: ProductAttribute): void {
        const superAttribute = this.attributesObject[selectedAttribute.value];

        this.selectedAttributes = [...this.selectedAttributes];
        this.selectedAttributes[index].attributes = superAttribute.attributes.filter((attribute) =>
            values.includes(attribute.value),
        );
        this.selectedAttributesChange.emit(this.selectedAttributes);
        this.cdr.markForCheck();
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
